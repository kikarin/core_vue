<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Repositories\RoleRepository;
use App\Repositories\UsersRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UsersController extends Controller implements HasMiddleware
{
    use BaseTrait;
    private $request;
    private $repository;
    private $roleRepository;

    public function __construct(Request $request, UsersRepository $repository, RoleRepository $roleRepository)
    {
        $this->repository = $repository;
        $this->roleRepository = $roleRepository;
        $this->request = UsersRequest::createFromBase($request);
        $this->initialize();
    }

    public static function middleware(): array
    {
        $className = class_basename(__CLASS__);
        $permission = str_replace('Controller', '', $className);
        $permission = trim(implode(' ', preg_split('/(?=[A-Z])/', $permission)));
        return [
            new Middleware("can:$permission Add", only: ['create', 'store']),
            new Middleware("can:$permission Detail", only: ['show']),
            new Middleware("can:$permission Edit", only: ['edit', 'update']),
            new Middleware("can:$permission Delete", only: ['destroy', 'destroy_selected']),
        ];
    }

    public function login_as($users_id = '')
    {
        $user = $this->repository->loginAs($users_id);
        $init_page_login = ($user->role->init_page_login != "") ? $user->role->init_page_login : 'dashboard';
        return redirect($init_page_login)->withSuccess("Login As successfully.");
    }
}