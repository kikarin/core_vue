<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Traits\RepositoryTrait;
use Illuminate\Support\Facades\DB;

class PermissionRepository
{
    use RepositoryTrait;
    protected $model;
    protected $categoryPermissionRepository;

    public function __construct(Permission $model, CategoryPermissionRepository $categoryPermissionRepository)
    {
        $this->model = $model;
        $this->categoryPermissionRepository = $categoryPermissionRepository;
    }

    public function customIndex($data)
    {
        $data += [
            'permissions' => $this->model
                ->with('category_permission')
                ->get()
                ->map(function ($perm) {
                    return [
                        'id' => $perm->id,
                        'name' => $perm->name,
                        'category_permission_id' => $perm->category_permission_id,
                        'category_permission' => optional($perm->category_permission)->name,
                    ];
                }),
        ];
        return $data;
    }

    public function customCreateEdit($data, $item = null)
    {
        $data += [
            'category_permission_id' => request()->input('category_permission_id'),
            'get_CategoryPermission' => $this->categoryPermissionRepository->getAll_OrderSequence()->pluck("name", "id"),
        ];
        return $data;
    }

    public function delete($id)
    {
        // Hapus relasi di tabel lain yang referensi ke permission ini
        DB::table('users_menus')->where('permission_id', $id)->delete();
        // Lanjut hapus permission
        try {
            DB::beginTransaction();
            $record = $this->getById($id);
            $model = $record;
            $model->delete();
            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function callbackAfterStoreOrUpdate($model, $data, $method = "store", $record_sebelumnya = null)
    {
        return redirect()->route('permissions.index')->with('success', ($method == "store") ? trans('message.success_add') : trans('message.success_update'));
    }

    public function callbackAfterDelete($model, $request)
    {
        return redirect()->route('permissions.index')->with('success', trans('message.success_delete'));
    }

    public function callbackAfterDeleteSelected($model, $request)
    {
        return redirect()->route('permissions.index')->with('success', trans('message.success_delete'));
    }
}
