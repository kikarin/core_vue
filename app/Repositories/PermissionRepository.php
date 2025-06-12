<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Traits\RepositoryTrait;

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
}
