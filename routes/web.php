<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersMenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ActivityLogController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Menu API
Route::get('/api/users', [UsersController::class, 'apiIndex']);
Route::get('/api/users-menu', [UsersMenuController::class, 'getMenus'])->middleware(['auth', 'verified']);
Route::get('/api/menus', [UsersMenuController::class, 'apiIndex']);
Route::get('/api/roles', [RoleController::class, 'apiIndex']);
Route::get('/api/activity-logs', [ActivityLogController::class, 'apiIndex']);

// Users Routes
Route::resource('/users', UsersController::class)->names('users');
Route::post('/users/destroy-selected', [UsersController::class, 'destroy_selected'])->name('users.destroy_selected');

// Teams Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/management/teams', function () {
        return Inertia::render('modules/teams/Index');
    })->name('management.teams.index');

    Route::get('/management/teams/create', function () {
        return Inertia::render('modules/teams/Create');
    })->name('management.teams.create');

    Route::get('/management/teams/{id}', function () {
        return Inertia::render('modules/teams/Show');
    })->name('management.teams.show');

    Route::get('/management/teams/{id}/edit', function () {
        return Inertia::render('modules/teams/Edit');
    })->name('management.teams.edit');
});

// Members Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/management/members', function () {
        return Inertia::render('modules/members/Index');
    })->name('management.members.index');

    Route::get('/management/members/create', function () {
        return Inertia::render('modules/members/Create');
    })->name('management.members.create');

    Route::get('/management/members/{id}', function () {
        return Inertia::render('modules/members/Show');
    })->name('management.members.show');

    Route::get('/management/members/{id}/edit', function () {
        return Inertia::render('modules/members/Edit');
    })->name('management.members.edit');
});

// Team Names Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/data-master/team-names', function () {
        return Inertia::render('modules/team-names/Index');
    })->name('data-master.team-names.index');

    Route::get('/data-master/team-names/create', function () {
        return Inertia::render('modules/team-names/Create');
    })->name('data-master.team-names.create');

    Route::get('/data-master/team-names/{id}', function () {
        return Inertia::render('modules/team-names/Show');
    })->name('data-master.team-names.show');

    Route::get('/data-master/team-names/{id}/edit', function () {
        return Inertia::render('modules/team-names/Edit');
    })->name('data-master.team-names.edit');
});

// Menus Routes
Route::middleware(['auth', 'verified'])->group(function () {
Route::resource('/menu-permissions/menus', UsersMenuController::class)
    ->names('menus');
    Route::post('/menu-permissions/menus/destroy-selected', [UsersMenuController::class, 'destroy_selected'])->name('menu-permissions.menus.destroy-selected');
});

// Roles Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/menu-permissions/roles', \App\Http\Controllers\RoleController::class)->names('roles');
    Route::get('/menu-permissions/roles/set-permissions/{id}', [\App\Http\Controllers\RoleController::class, 'set_permission'])->name('roles.set-permission');
    Route::post('/menu-permissions/roles/set-permissions/{id}', [\App\Http\Controllers\RoleController::class, 'set_permission_action'])->name('roles.set-permission-action');
    Route::post('/menu-permissions/roles/destroy-selected', [\App\Http\Controllers\RoleController::class, 'destroy_selected'])->name('roles.destroy_selected');
});

// Permissions & Category Permissions Routes
Route::middleware(['auth', 'verified'])->prefix('menu-permissions')->group(function () {
    // Permission (Child) - PASTIKAN INI DI ATAS resource
    Route::get('/permissions/create-permission', [\App\Http\Controllers\PermissionController::class, 'create'])->name('permissions.create-permission');
    Route::post('/permissions/store-permission', [\App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store-permission');
    Route::get('/permissions/{id}/edit-permission', [\App\Http\Controllers\PermissionController::class, 'edit'])->name('permissions.edit-permission');
    Route::put('/permissions/update-permission/{id}', [\App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update-permission');
    Route::get('/permissions/{id}/detail', [\App\Http\Controllers\PermissionController::class, 'show'])->name('permissions.detail');
    Route::delete('/permissions/delete-permission/{id}', [\App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.delete-permission');

    // Kategori Permission (Category)
    Route::resource('/permissions', \App\Http\Controllers\CategoryPermissionController::class)->names('permissions');
    // Custom: Show/Edit kategori by /category/{id}
    Route::get('/permissions/category/{id}', [\App\Http\Controllers\CategoryPermissionController::class, 'show'])->name('permissions.category.show');
    Route::get('/permissions/category/{id}/edit', [\App\Http\Controllers\CategoryPermissionController::class, 'edit'])->name('permissions.category.edit');
});

// Activity Logs Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/menu-permissions/logs', fn () => Inertia::render('modules/activity-logs/Index'))->name('access-control.logs.index');
    Route::get('/menu-permissions/logs/{id}', fn () => Inertia::render('modules/activity-logs/Show'))->name('access-control.logs.show');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

