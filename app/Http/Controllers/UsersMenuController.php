<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersMenuRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\UsersMenuRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UsersMenuController extends Controller implements HasMiddleware
{
    use BaseTrait;
    private $request;
    private $repository;
    private $permissionRepository;

    public function __construct(Request $request, UsersMenuRepository $repository, PermissionRepository $permissionRepository)
    {
        $this->repository           = $repository;
        $this->permissionRepository = $permissionRepository;
        $this->initialize();
        $this->request                        = UsersMenuRequest::createFromBase($request);
        $this->route                          = 'menus';
        $this->commonData['kode_first_menu']  = 'USERS-MANAGEMENT';
        $this->commonData['kode_second_menu'] = $this->kode_menu;
    }

    public static function middleware(): array
    {
        $className  = class_basename(__CLASS__);
        $permission = str_replace('Controller', '', $className);
        $permission = trim(implode(' ', preg_split('/(?=[A-Z])/', $permission)));
        return [
            new Middleware("can:$permission Add", only: ['create', 'store']),
            new Middleware("can:$permission Detail", only: ['show']),
            new Middleware("can:$permission Edit", only: ['edit', 'update']),
            new Middleware("can:$permission Delete", only: ['destroy', 'destroy_selected']),
        ];
    }

    /**
     * Get menus for API with permission filtering
     * This endpoint is used by the sidebar component
     */
    public function getMenus()
    {
        try {
            $menus = $this->repository->getMenus();

            return response()->json([
                'success' => true,
                'data'    => $menus,
                'message' => 'Menus retrieved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Failed to retrieve menus: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API endpoint for data table (admin interface)
     * This shows all menus regardless of permission for management purposes
     */
    public function apiIndex()
    {
        try {
            $data = $this->repository->customIndex([]);

            return response()->json([
                'success' => true,
                'data'    => $data['menus'],
                'meta'    => [
                    'total'        => $data['meta']['total'],
                    'current_page' => $data['meta']['current_page'],
                    'per_page'     => $data['meta']['per_page'],
                    'search'       => $data['meta']['search'],
                    'sort'         => $data['meta']['sort'],
                    'order'        => $data['meta']['order'],
                ],
                'listUsersMenu' => $data['listUsersMenu'],
                'permissions'   => $data['get_Permission'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menu data: ' . $e->getMessage(),
            ], 500);
        }
    }

}
