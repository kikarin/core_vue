<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use App\Traits\RepositoryTrait;

class ActivityLogRepository
{
    use RepositoryTrait;

    public function __construct(ActivityLog $model)
    {
        $this->model = $model;
    }

    public function customTable($table, $data, $request)
    {
        return $table->editColumn('causer.name', function ($d) {
            $causer_name = @$d->causer->name ?? null;
            $causer_role_name = @$d->causer->role->name ?? null;
            $causer_name = $causer_name . " <br> <small class='text-muted'>" . $causer_role_name . "</small>";
            return $causer_name;
        })->editColumn('causer.role.name', function ($d) {
            return @$d->causer->role->name ?? null;
        })->rawColumns(['causer.name']);
    }

    public function customIndex($data)
    {
        $query = $this->model
            ->with(['causer', 'causer.role'])
            ->select('id', 'log_name', 'description', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'created_at', 'event');

        // Apply search
        if (request('search')) {
            $searchTerm = request('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('log_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('subject_type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('event', 'like', '%' . $searchTerm . '%');
            });
        }

        // Apply sorting
        if (request('sort')) {
            $order = request('order', 'asc');
            $sortMapping = [
                'module' => 'log_name',
                'event' => 'event',
                'subject_type' => 'subject_type',
                'subject_id' => 'subject_id',
                'data' => 'description', // Map data to description for sorting
                'created_at' => 'created_at',
            ];
            $sortColumn = $sortMapping[request('sort')] ?? 'created_at';
            $query->orderBy($sortColumn, $order);
        } else {
            $query->orderBy('created_at', 'desc'); // default sort
        }

        // Apply pagination
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 0);
        $pageForLaravel = $page < 1 ? 1 : $page + 1;

        $logs = $query->paginate($perPage, ['*'], 'page', $pageForLaravel);

        // Transform data
        $transformedLogs = $logs->getCollection()->map(function ($log) {
            return [
                'id' => $log->id,
                'module' => $log->log_name,
                'event' => $log->event,
                'subject_type' => class_basename($log->subject_type),
                'subject_id' => $log->subject_id,
                'data' => $log->description,
                'causer_name' => optional($log->causer)->name,
                'causer_role' => optional(optional($log->causer)->role)->name,
                'created_at' => $log->created_at,
            ];
        });

        $data += [
            'logs' => $transformedLogs,
            'meta' => [
                'total' => $logs->total(),
                'current_page' => $logs->currentPage(),
                'per_page' => $logs->perPage(),
                'search' => request('search', ''),
                'sort' => request('sort', ''),
                'order' => request('order', 'asc'),
            ],
        ];

        return $data;
    }

    public function getDetailWithUserTrack($id)
    {
        return $this->model->with(['causer', 'causer.role'])->find($id);
    }

    public function deleteSelected(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
