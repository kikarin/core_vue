<?php

namespace App\Repositories;

use App\Models\User;
use App\Traits\RepositoryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersRepository
{
    use RepositoryTrait;
    protected $model;
    protected $usersRoleRepository;
    protected $roleRepository;


    public function __construct(User $model, RoleRepository $roleRepository, UsersRoleRepository $usersRoleRepository)
    {
        $this->model = $model;
        $this->roleRepository = $roleRepository;
        $this->usersRoleRepository = $usersRoleRepository;

        $this->with = ["users_role", "role"];
    }

    public function customIndex($data)
    {
        $query = $this->model
            ->with('role')
            ->select('id', 'name', 'email', 'current_role_id', 'is_active');

        // Apply search
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        }

        // Apply sorting
        if (request('sort')) {
            $order = request('order', 'asc');
            $query->orderBy(request('sort'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Get paginated results
        $users = $query->paginate(request('per_page', 10))->withQueryString();

        // Transform data untuk frontend
        $transformedUsers = collect($users->items())->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? $user->role->name : '-',
                'is_active' => $user->is_active,
            ];
        });

        $data += [
            "listRole" => $this->roleRepository->getAll()->pluck('name', 'id')->toArray(),
            "users" => $transformedUsers,
            "total" => $users->total(),
            "currentPage" => $users->currentPage(),
            "perPage" => $users->perPage(),
            "search" => request('search', ''),
            "sort" => request('sort', ''),
            "order" => request('order', 'asc'),
        ];

        return $data;
    }


    public function customCreateEdit($data, $item = null)
    {
        $data += [
            'get_Roles' => $this->roleRepository->getAll()->pluck('name', 'id')->toArray(),
        ];
        return $data;
    }

    public function customDataCreateUpdate($data, $record = null)
    {
        $userId = Auth::id();

        if (is_null($record)) {
            $data['created_by'] = $userId;
        }
        $data['updated_by'] = $userId;

        if (!empty($data['password'])) {
            $data['password'] = !empty($data['disabled_hash_password'])
                ? $data['password']
                : Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['current_role_id'] = isset($data['role_id'][0]) ? $data['role_id'][0] : null;

        unset($data['disabled_hash_password']);

        return $data;
    }

    public function callbackAfterStoreOrUpdate($model, $data, $method = "store", $record_sebelumnya = null)
    {
        if (@$data['is_delete_foto'] == 1) {
            $model->clearMediaCollection('images');
        }

        if (@$data['file']) {
            $media = $model->addMedia($data['file'])->usingName($data['name'])->toMediaCollection('images');

            Storage::disk($media->disk)->delete($media->getPathRelativeToRoot());
        }
        $model->assignRole((int) $model->current_role_id);
        $this->usersRoleRepository->setRole($model->id, $data['role_id']);

        if (@$data['from_menu'] == "pencaker") {
            return redirect()->back()->with('success', 'Berhasil update data');
        }
        return $model;
    }

    public function getByEmail($email)
    {
        $record = $this->model::where("email", $email)->first();
        return $record;
    }

    public function loginAs($userId)
    {
        $record = $this->getById($userId);
        $users_id_lama = Auth::user()->id;
        if (Auth::loginUsingId($record->id, false)) {
            request()->session()->put('is_login_as', 1);
            request()->session()->put('users_id_lama', $users_id_lama);
            activity()->event('Login As')->performedOn($record)->log("User");
        }
        return Auth::user();
    }

    public function delete_selected(array $ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }


    public function getDetailWithUserTrack($id)
    {
        return $this->model
            ->with(['role', 'created_by_user', 'updated_by_user'])
            ->where('id', $id)
            ->first();
    }

    public function handleShow($id)
    {
        $item = $this->getDetailWithUserTrack($id);

        if (!$item) {
            return redirect()->back()->with('error', 'User not found');
        }

        return \Inertia\Inertia::render('modules/users/Show', [
            'item' => $item
        ]);
    }

}
