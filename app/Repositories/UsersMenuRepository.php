<?php

namespace App\Repositories;

use App\Models\UsersMenu;
use App\Traits\RepositoryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class UsersMenuRepository
{
    use RepositoryTrait;
    private $cacheKey = 'UsersMenu_cache';
    protected $model;
    protected $permissionRepository;

    public function __construct(UsersMenu $model, PermissionRepository $permissionRepository)
    {
        $this->model = $model;
        $this->with  = [
            'children',
            'rel_users_menu',
            'permission',
        ];

        $this->permissionRepository = $permissionRepository;
        $this->with                 = ['rel_users_menu', 'permission', 'created_by_user', 'updated_by_user'];
    }

    public function getByRel($rel)
    {
        return $this->model::with($this->with)->where('rel', $rel)->orderBy('urutan', 'asc')->get();
    }

    public function listDropdown()
    {
        $list    = [];
        $list[0] = 'Menu Utama';
        $data    = $this->getByRel(0);
        foreach ($data as $key => $value) {
            $list[$value->id] = $value->nama;
            foreach ($value->children as $k => $v) {
                $list[$v->id] = '===' . $v->nama;
                foreach ($v->children as $s => $d) {
                    $list[$d->id] = '======' . $d->nama;
                }
            }
        }
        return $list;
    }

    public function updateCache()
    {
        $usersMenu = $this->model::get();
        $usersMenu = collect($usersMenu);
        Cache::put($this->cacheKey, $usersMenu, now()->addDay());
        $usersMenu = Cache::get($this->cacheKey);
        return $usersMenu;
    }

    public function getCache()
    {
        if (Cache::has($this->cacheKey)) {
            $usersMenu = Cache::get($this->cacheKey);
            $usersMenu = collect($usersMenu);
            return $usersMenu;
        } else {
            return $this->updateCache();
        }
    }

    public function getCacheByKode($kode)
    {
        $getCache = $this->getCache();
        $getCache = $getCache->where('kode', $kode)->first();
        return $getCache;
    }

    public function forgetCache()
    {
        Cache::forget($this->cacheKey);
    }

    public function customIndex($data)
    {
        $query = $this->model
            ->with(['rel_users_menu', 'permission'])
            ->select('id', 'nama', 'kode', 'icon', 'rel', 'url', 'urutan', 'permission_id');

        // Apply search
        if (request('search')) {
            $searchTerm = request('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('kode', 'like', '%' . $searchTerm . '%')
                    ->orWhere('url', 'like', '%' . $searchTerm . '%');
            });
        }

        // Apply sorting
        if (request('sort')) {
            $order = request('order', 'asc');
            // Mapping nama kolom frontend ke nama kolom database
            $sortMapping = [
                'name'   => 'nama',
                'code'   => 'kode',
                'url'    => 'url',
                'parent' => 'rel',
                'order'  => 'urutan',
            ];

            $sortColumn = $sortMapping[request('sort')] ?? 'urutan';
            $query->orderBy($sortColumn, $order);
        } else {
            $query->orderBy('urutan'); // default sort
        }

        // Apply pagination
        $perPage        = (int) request('per_page', 10);
        $page           = (int) request('page', 0);
        $pageForLaravel = $page < 1 ? 1 : $page + 1;

        $menus = $query->paginate($perPage, ['*'], 'page', $pageForLaravel);

        // Transform data
        $transformedMenus = $menus->getCollection()->map(function ($menu) {
            return [
                'id'         => $menu->id,
                'name'       => $menu->nama,
                'code'       => $menu->kode,
                'icon'       => $menu->icon,
                'parent'     => optional($menu->rel_users_menu)->nama ?? '-',
                'permission' => optional($menu->permission)->name ?? '-',
                'url'        => $menu->url,
                'order'      => $menu->urutan,
            ];
        });

        $data += [
            'listUsersMenu'  => $this->listDropdown(),
            'get_Permission' => $this->permissionRepository->getAll()->pluck('name', 'id'),
            'menus'          => $transformedMenus,
            'meta'           => [
                'total'        => $menus->total(),
                'current_page' => $menus->currentPage(),
                'per_page'     => $menus->perPage(),
                'search'       => request('search', ''),
                'sort'         => request('sort', ''),
                'order'        => request('order', 'asc'),
            ],
        ];

        return $data;
    }

    public function customCreateEdit($data, $item = null)
    {
        $data += [
            'listUsersMenu'  => $this->listDropdown(),
            'get_Permission' => $this->permissionRepository->getAll()->pluck('name', 'id'),
        ];
        return $data;
    }

    /**
     * Get menus with permission filtering
     * Mengecek permission "Show" untuk setiap menu
     */
    public function getMenus()
    {
        $user = Auth::user();
        if (!$user || !$user->role) {
            return collect([]);
        }

        $roleId = $user->role->id;

        // Cache key berdasarkan role agar tiap role punya cache menu sendiri
        $cacheKey = "menus_for_role_{$roleId}";

        // Cache selama 30 menit
        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($roleId) {
            $menus = $this->model
                ->with('children.children.children', 'permission')
                ->select('id', 'nama', 'kode', 'icon', 'rel', 'url', 'urutan', 'permission_id')
                ->where('rel', 0)
                ->orderBy('urutan')
                ->get();

            // Ambil kembali role user (jika kamu butuh model lengkap)
            $role = Auth::user()->role;

            // Filter menu berdasarkan permission
            return $this->filterMenusByPermission($menus, $role);
        });
    }

    /**
     * Recursively filter menus based on permission
     */
    private function filterMenusByPermission($menus, $role)
    {
        return $menus->filter(function ($menu) use ($role) {
            // Check if menu has permission requirement
            if ($menu->permission) {
                $permissionName = $menu->permission->name;
                try {
                    $hasPermission = $role->hasPermissionTo($permissionName);
                    if (!$hasPermission) {
                        return false;
                    }
                } catch (PermissionDoesNotExist $e) {
                    // If permission doesn't exist, allow access
                }
            }

            // If menu has children, filter them recursively
            if ($menu->children && $menu->children->count() > 0) {
                $filteredChildren = $this->filterMenusByPermission($menu->children, $role);
                $menu->setRelation('children', $filteredChildren);
                if ($filteredChildren->count() === 0) {
                    return false;
                }
            }

            return true;
        })->values();
    }

    public function deleteSelected(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
        $this->forgetCache();
    }

    public function getDetailWithUserTrack($id)
    {
        $item = $this->getFind($id);
        if (!$item) {
            return null;
        }
        return $this->model->with(['rel_users_menu', 'permission', 'created_by_user', 'updated_by_user'])
            ->find($id);
    }

    public function customDataCreateUpdate($data, $record = null)
    {
        $result = [];

        if ($record == null) {
            // Create
            $result['created_by'] = Auth::id();
        }
        $result['updated_by'] = Auth::id();

        // Pastikan nilai numerik
        $result['rel']           = isset($data['rel']) ? (int) $data['rel'] : 0;
        $result['permission_id'] = isset($data['permission_id']) ? (int) $data['permission_id'] : null;
        $result['urutan']        = isset($data['urutan']) ? (int) $data['urutan'] : 1;

        // Tambahkan field lain
        $result['nama'] = $data['nama'] ?? '';
        $result['kode'] = $data['kode'] ?? '';
        $result['icon'] = $data['icon'] ?? '';
        $result['url']  = $data['url']  ?? '';

        // Tambahkan id jika mode update
        if ($record !== null) {
            $result['id'] = $record;
        }

        return $result;
    }

    public function callbackAfterStoreOrUpdate($model, $data, $method = 'store', $record_sebelumnya = null)
    {
        // Hapus cache setelah store/update
        $this->forgetCache();

        // Update cache dengan data terbaru
        $this->updateCache();

        return $model;
    }

    public function create($data)
    {
        $record = $this->model->create($data);
        $this->forgetCache();
        $this->updateCache();
        return $record;
    }

    public function update($id, $data)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
            $this->forgetCache();
            $this->updateCache();
        }
        return $record;
    }

    public function delete($id)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->delete();
            $this->forgetCache();
            $this->updateCache();
        }
        return $record;
    }
}
