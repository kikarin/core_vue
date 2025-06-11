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
        $data += [
            "listRole" => $this->roleRepository->getAll()->pluck('name', 'id')->toArray(),
            "users" => $this->model
                ->with('role')
                ->select('id', 'name', 'email', 'current_role_id', 'is_active')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => optional($user->role)->name,
                        'status' => $user->is_active ? 'Active' : 'Inactive',
                    ];
                }),
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
        if ($record == null) {
            // Create
            $data['created_by'] = Auth::id();
        }
        $data['updated_by'] = Auth::id();

        if (@$data['password'] != "" and @$data['password'] != null) {
            if (@$data['disabled_hash_password'] == true) {
                $data['password'] = $data['password'];
            } else {
                $data['password'] = Hash::make($data['password']);
            }
        } else {
            unset($data['password']);
        }
        $role_id_array = $data['role_id'];
        $data['current_role_id'] = $role_id_array[0];
        // unset($data['role_id']);
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

    public function customTable($table, $data, $request)
    {
        return $table->editColumn('email', function ($d) {
            return $d->email . " <br> <small class='text-muted'>" . $d->no_hp . "</small>";
        })->editColumn('file', function ($d) {
            return $d->file_url_image;
        })->addColumn('list_role_name_str', function ($d) {
            return $d->list_role_name_str;
        })->editColumn('is_active', function ($d) {
            return $d->is_active_badge;
        })->editColumn('created_at', function ($d) {
            return $d->created_at;
        })->rawColumns(["email", "file", "list_role_name_str", "is_active"]);
    }

    public function getByEmail($email)
    {
        $record = $this->model::where("email", $email)->first();
        return $record;
    }

    public function getByFile($data)
    {
        $record = $this->model::where("file", $data['file_name']);
        if (array_key_exists("is_my_file", $data) && $data["is_my_file"] != "") {
            $record = $record->where("id", Auth::user()->id);
        }
        $record = $record->first();
        $record['file_path'] = $record["file_path"];
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
