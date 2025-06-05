<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TeamController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/management/users', function () {
    return Inertia::render('modules/users/Index');
})->name('management.users');
Route::get('/management/users/create', function () {
    return Inertia::render('modules/users/Create');
})->name('management.users.create');

Route::get('/management/users/{id}/edit', function () {
    return Inertia::render('modules/users/Edit');
})->name('management.users.edit');

Route::get('/management/users/{id}', function () {
    return Inertia::render('modules/users/Show');
})->name('management.users.show');

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

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

