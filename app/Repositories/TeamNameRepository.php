<?php

namespace App\Repositories;

use App\Models\TeamName;
use App\Traits\RepositoryTrait;

class TeamNameRepository
{
    use RepositoryTrait;

    protected $model;

    public function __construct(TeamName $model)
    {
        $this->model            = $model;
    }

    public function customIndex($data)
    {
        $query = $this->model
            ->select('id', 'nama')
            ->orderBy('id', 'desc');

        // Apply search
        if (request('search')) {
            $query->where('nama', 'like', '%' . request('search') . '%');
        }

        // Apply sorting
        if (request('sort')) {
            $order = request('order', 'asc');
            $query->orderBy(request('sort'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        // --- PAGINATION FIX ---
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        if ($perPage === -1) {
            $allTeams = $query->get();
            $transformedTeams = collect($allTeams)->map(function ($team) {
                return [
                    'id' => $team->id,
                    'nama' => $team->nama,
                ];
            });
            $data += [
                'teams' => $transformedTeams,
                'meta' => [ 
                    'total' => $allTeams->count(),
                    'current_page' => 1,
                    'per_page' => $allTeams->count(),
                    'search' => request('search', ''),
                    'sort' => request('sort', ''),
                    'order' => request('order', 'asc'),
                ],
            ];
            return $data;
        }
        return $query;
    }

    public function customCreateEdit($data)
    {
        $data += [
            'teams' => $this->model->select('id', 'nama')->get(),
        ];
        return $data;
    }

}
