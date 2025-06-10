<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\UsersController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('/users', UsersController::class)->names('users');

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
    Route::get('/menu-permissions/menus', function () {
        return Inertia::render('modules/menus/Index');
    })->name('menu-permissions.menus.index');

    Route::get('/menu-permissions/menus/create', function () {
        return Inertia::render('modules/menus/Create');
    })->name('menu-permissions.menus.create');

    Route::get('/menu-permissions/menus/{id}', function () {
        return Inertia::render('modules/menus/Show');
    })->name('menu-permissions.menus.show');

    Route::get('/menu-permissions/menus/{id}/edit', function () {
        return Inertia::render('modules/menus/Edit');
    })->name('menu-permissions.menus.edit');
});

// Roles Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/menu-permissions/roles', fn() => Inertia::render('modules/roles/Index'))->name('access-control.roles.index');
    Route::get('/menu-permissions/roles/create', fn() => Inertia::render('modules/roles/Create'))->name('access-control.roles.create');
    Route::get('/menu-permissions/roles/{id}', fn() => Inertia::render('modules/roles/Show'))->name('access-control.roles.show');
    Route::get('/menu-permissions/roles/{id}/edit', fn() => Inertia::render('modules/roles/Edit'))->name('access-control.roles.edit');
    Route::get('/menu-permissions/roles/set-permissions/{id}', function () {
        return Inertia::render('modules/roles/SetPermissions');
    })->name('access-control.roles.set-permissions')->middleware(['auth', 'verified']);
});

// Category Permissions
Route::middleware(['auth', 'verified'])->prefix('menu-permissions')->group(function () {
    Route::get('/permissions', fn() => Inertia::render('modules/permissions/Index'))->name('permissions.index');
    Route::get('/permissions/create', fn() => Inertia::render('modules/permissions/Create'))->name('permissions.create');
    Route::get('/permissions/category/{id}', fn() => Inertia::render('modules/permissions/Show'))->name('permissions.show');
    Route::get('/permissions/category/{id}/edit', fn() => Inertia::render('modules/permissions/Edit'))->name('permissions.edit');
    
    Route::get('/permissions/create-permission', fn() => Inertia::render('modules/permissions/PermissionCreate'))->name('permissions.create-permission');
    Route::get('/permissions/{id}/edit-permission', fn() => Inertia::render('modules/permissions/PermissionEdit'))->name('permissions.edit-permission');
    Route::get('/permissions/{id}/detail', fn() => Inertia::render('modules/permissions/PermissionDetail'))->name('permissions.detail');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

