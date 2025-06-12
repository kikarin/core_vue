<?php

namespace App\Repositories;

use App\Models\Role;
use App\Traits\RepositoryTrait;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class RoleRepository
{
    use RepositoryTrait;

    protected $model;

    public function __construct(Role $model)
    {
        $this->model                   = $model;
    }

    public function customCreateEdit($data, $item = null)
    {
        $data += [
            'listBg'        => $this->model->listBg(),
            'listInitPage'  => $this->model->listInitPage(),
        ];
        return $data;
    }

    public function customTable($table, $data, $request)
    {
        return $table->editColumn('is_allow_login', function ($d) {
            return $d->is_allow_login_badge;
        })->editColumn('is_vertical_menu', function ($d) {
            return $d->is_vertical_menu_badge;
        })->editColumn('bg', function ($d) {
            return $d->bg_badge;
        })->rawColumns(['is_allow_login', 'is_vertical_menu', 'bg']);
    }

    public function setPermission($id, $permission_id_array = [])
    {
        $record = $this->getById($id);
        try {
            DB::beginTransaction();
            $properties['old'] = $record->permissions()->pluck('name')->toArray();
            $permission_id_array = array_map('intval', $permission_id_array);
            $record->syncPermissions($permission_id_array);
            $properties['attributes'] = $record->permissions()->pluck('name')->toArray();
            activity()->event('Set Permission')->performedOn($record)->withProperties($properties)->log("User");
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function customIndex($data)
    {
        $data += [
            'roles' => $this->model
                ->select('id', 'name', 'bg', 'init_page_login', 'is_allow_login', 'is_vertical_menu', 'created_at', 'updated_at')
                ->get()
                ->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'bg' => $role->bg,
                        'init_page_login' => $role->init_page_login,
                        'is_allow_login' => $role->is_allow_login ? 'Ya' : 'Tidak',
                        'is_vertical_menu' => $role->is_vertical_menu ? 'Vertical' : 'Horizontal',
                        'created_at' => $role->created_at,
                        'updated_at' => $role->updated_at,
                    ];
                }),
        ];
        return $data;
    }

    public function customShow($data, $item = null)
    {
        if ($item) {
            $data['item'] = [
                'id' => $item->id,
                'name' => $item->name,
                'bg' => $item->bg,
                'init_page_login' => $item->init_page_login,
                'is_allow_login' => $item->is_allow_login ? 'Ya' : 'Tidak',
                'is_vertical_menu' => $item->is_vertical_menu ? 'Vertical' : 'Horizontal',
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        }
        return $data;
    }

    public function customDataCreateUpdate($data, $record = null)
    {
        $result = [];
        if ($record == null) {
            $result['created_by'] = Auth::id();
        }
        $result['updated_by'] = Auth::id();
        $result['name'] = $data['name'] ?? '';
        $result['bg'] = $data['bg'] ?? null;
        $result['init_page_login'] = $data['init_page_login'] ?? null;
        $result['is_allow_login'] = isset($data['is_allow_login']) ? (bool) $data['is_allow_login'] : true;
        $result['is_vertical_menu'] = isset($data['is_vertical_menu']) ? (bool) $data['is_vertical_menu'] : true;
        if ($record !== null) {
            $result['id'] = $record;
        }
        return $result;
    }
}
