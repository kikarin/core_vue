<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersMenuRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\UsersMenuRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class UsersMenuController extends Controller implements HasMiddleware
{
    use BaseTrait;
    private $repository;
    private $permissionRepository;

    public function __construct(UsersMenuRepository $repository, PermissionRepository $permissionRepository)
    {
        $this->repository = $repository;
        $this->permissionRepository = $permissionRepository;
        $this->initialize();
        $this->route = 'menus';
        $this->commonData['kode_first_menu'] = "USERS-MANAGEMENT";
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
    public function getMenus()
    {
        return response()->json($this->repository->getMenus());
    }
}
