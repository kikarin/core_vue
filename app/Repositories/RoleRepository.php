<?php

namespace App\Repositories;

use App\Models\Role;
use App\Traits\RepositoryTrait;
use Illuminate\Support\Facades\DB;
use Exception;

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
}
