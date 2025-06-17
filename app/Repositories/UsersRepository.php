<?php

namespace App\Repositories;

use App\Models\User;
use App\Traits\RepositoryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersRepository
{
    use RepositoryTrait;
    protected $model;
    protected $usersRoleRepository;
    protected $roleRepository;

    public function __construct(User $model, RoleRepository $roleRepository, UsersRoleRepository $usersRoleRepository)
    {
        $this->model = $model;
        $this->roleRepository = $roleRepository;
        $this->usersRoleRepository = $usersRoleRepository;

        $this->with = ["users_role", "role", "created_by_user", "updated_by_user"];
    }

    public function customIndex($data)
    {
        $query = $this->model
            ->with(['role', 'users_role.role'])
            ->select('id', 'name', 'email', 'current_role_id', 'is_active');

        // Apply search
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        }

        // Apply sorting - FIX: Handle relationship sorting
        if (request('sort')) {
            $order = request('order', 'asc');
            $sortField = request('sort');
            
            // Handle role sorting by joining with roles table
            if ($sortField === 'role') {
                $query->leftJoin('roles', 'users.current_role_id', '=', 'roles.id')
                      ->orderBy('roles.name', $order)
                      ->select('users.id', 'users.name', 'users.email', 'users.current_role_id', 'users.is_active');
            } else {
                // For other fields, check if it's a valid column in users table
                $validColumns = ['id', 'name', 'email', 'current_role_id', 'is_active', 'created_at', 'updated_at'];
                if (in_array($sortField, $validColumns)) {
                    $query->orderBy('users.' . $sortField, $order);
                } else {
                    // Default sorting if invalid sort field
                    $query->orderBy('users.id', 'desc');
                }
            }
        } else {
            $query->orderBy('users.id', 'desc');
        }

        // --- PAGINATION FIX ---
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        if ($perPage === -1) {
            $allUsers = $query->get();
            $transformedUsers = collect($allUsers)->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ? $user->role->name : '-',
                    'all_roles' => $user->list_role_name_str, // Show all roles
                    'is_active' => $user->is_active,
                ];
            });
            $data += [
                "listRole" => $this->roleRepository->getAll()->pluck('name', 'id')->toArray(),
                "users" => $transformedUsers,
                "total" => $transformedUsers->count(),
                "currentPage" => 1,
                "perPage" => -1,
                "search" => request('search', ''),
                "sort" => request('sort', ''),
                "order" => request('order', 'asc'),
            ];
            return $data;
        }

        // Fix pagination calculation
        $pageForPaginate = $page < 1 ? 1 : $page;
        $users = $query->paginate($perPage, ['*'], 'page', $pageForPaginate)->withQueryString();

        $transformedUsers = collect($users->items())->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? $user->role->name : '-',
                'all_roles' => $user->list_role_name_str, // Show all roles
                'is_active' => $user->is_active,
            ];
        });

        $data += [
            "listRole" => $this->roleRepository->getAll()->pluck('name', 'id')->toArray(),
            "users" => $transformedUsers,
            "total" => $users->total(),
            "currentPage" => $users->currentPage(),
            "perPage" => $users->perPage(),
            "search" => request('search', ''),
            "sort" => request('sort', ''),
            "order" => request('order', 'asc'),
        ];

        return $data;
    }

    public function customCreateEdit($data, $item = null)
    {
        $roles = $this->roleRepository->getAll();

        $data += [
            'get_Roles' => $roles->pluck('name', 'id')->toArray(),
            'roles' => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description ?? '',
                ];
            })->toArray(),
        ];

        // PERBAIKAN: Jika edit, ambil role yang sudah dipilih dengan error handling
        if ($item) {
            try {
                // Pastikan relasi users_role sudah di-load
                if (!$item->relationLoaded('users_role')) {
                    $item->load('users_role');
                }
                $data['selected_roles'] = $item->users_role_id_array;
            } catch (\Exception $e) {
                // Fallback jika ada error
                $data['selected_roles'] = [];
                Log::warning('Error loading user roles: ' . $e->getMessage());
            }
        } else {
            $data['selected_roles'] = [];
        }

        return $data;
    }
    
    public function customDataCreateUpdate($data, $record = null)
    {
        $userId = Auth::id();

        if (is_null($record)) {
            $data['created_by'] = $userId;
        }
        $data['updated_by'] = $userId;

        // Handle password
        if (!empty($data['password'])) {
            $data['password'] = !empty($data['disabled_hash_password'])
                ? $data['password']
                : Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // PERBAIKAN: Validasi role_id yang lebih robust
        if (!isset($data['role_id']) || !is_array($data['role_id']) || empty($data['role_id'])) {
            throw new \Exception('Role harus dipilih minimal 1');
        }

        // PERBAIKAN: Validasi apakah semua role_id ada di database
        $validRoles = $this->roleRepository->getAll()->pluck('id')->toArray();
        $invalidRoles = array_diff($data['role_id'], $validRoles);

        if (!empty($invalidRoles)) {
            throw new \Exception('Role tidak valid: ' . implode(', ', $invalidRoles));
        }

        // Set current_role_id ke role pertama yang dipilih
        $data['current_role_id'] = $data['role_id'][0];

        // PERBAIKAN: Bersihkan data yang tidak perlu
        unset($data['disabled_hash_password']);

        return $data;
    }

    public function callbackAfterStoreOrUpdate($model, $data, $method = "store", $record_sebelumnya = null)
    {
        try {
            DB::beginTransaction();

            // Handle file upload
            if (@$data['is_delete_foto'] == 1) {
                $model->clearMediaCollection('images');
            }

            if (@$data['file']) {
                $media = $model->addMedia($data['file'])->usingName($data['name'])->toMediaCollection('images');
                Storage::disk($media->disk)->delete($media->getPathRelativeToRoot());
            }

            // Validasi role_id sebelum sync
            if (!isset($data['role_id']) || !is_array($data['role_id']) || empty($data['role_id'])) {
                throw new \Exception('Role harus dipilih minimal 1');
            }

            // Validasi apakah semua role_id ada di database
            $validRoles = $this->roleRepository->getAll()->pluck('id')->toArray();
            $invalidRoles = array_diff($data['role_id'], $validRoles);

            if (!empty($invalidRoles)) {
                throw new \Exception('Role tidak valid: ' . implode(', ', $invalidRoles));
            }

            // Sync roles dengan Spatie Permission
            $roleNames = $this->roleRepository->getAll()
                ->whereIn('id', $data['role_id'])
                ->pluck('name')
                ->toArray();

            $model->syncRoles([(int) $model->current_role_id]);

            // Set roles di tabel users_role
            $this->usersRoleRepository->setRole($model->id, $data['role_id']);

            DB::commit();

            if (@$data['from_menu'] == "pencaker") {
                return redirect()->back()->with('success', 'Berhasil update data');
            }

            return $model;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getByEmail($email)
    {
        $record = $this->model::where("email", $email)->first();
        return $record;
    }

    public function loginAs($userId)
    {
        $record = $this->getById($userId);
        $users_id_lama = Auth::user()->id;
        if (Auth::loginUsingId($record->id, false)) {
            request()->session()->put('is_login_as', 1);
            request()->session()->put('users_id_lama', $users_id_lama);
            activity()->event('Login As')->performedOn($record)->log("User");
        }
        return Auth::user();
    }

    public function delete_selected(array $ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function getDetailWithUserTrack($id)
    {
        return $this->model
            ->with(['role', 'created_by_user', 'updated_by_user', 'users_role.role'])
            ->where('id', $id)
            ->first();
    }

    public function handleShow($id)
    {
        $item = $this->getDetailWithUserTrack($id);

        if (!$item) {
            return redirect()->back()->with('error', 'User not found');
        }

        return \Inertia\Inertia::render('modules/users/Show', [
            'item' => $item
        ]);
    }
}