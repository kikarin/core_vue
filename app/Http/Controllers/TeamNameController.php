<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamNameRequest;
use App\Repositories\TeamNameRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TeamNameController extends Controller implements HasMiddleware
{
    use BaseTrait;
    private $repository;
    private $request;

    public function __construct(TeamNameRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = TeamNameRequest::createFromBase($request);
        $this->initialize();
        $this->route = 'team-names';
        $this->commonData['kode_first_menu']  = $this->kode_menu;
        $this->commonData['kode_second_menu'] = null;
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
    public function apiIndex()
    {
        $data = $this->repository->customIndex([]);

        return response()->json([
            'data' => $data['teamNames'],
            'meta' => [
                'total' => $data['meta']['total'],
                'current_page' => $data['meta']['current_page'],
                'per_page' => $data['meta']['per_page'],
                'search' => $data['meta']['search'],
                'sort' => $data['meta']['sort'],
                'order' => $data['meta']['order'],
            ],
        ]);
    }
}
