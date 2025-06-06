<?php

namespace App\Repositories;

use App\Traits\RepositoryTrait;
use Illuminate\Support\Facades\Cache;

class DashboardRepository
{
    use RepositoryTrait;


    public function __construct(
    ) {
    }

    public function customIndex($data)
    {
        return $data;
    }
}
