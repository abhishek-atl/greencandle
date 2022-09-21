<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\EntryController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin'
], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('.dashboard');

    Route::group([
        'prefix' => 'groups',
        'as' => '.groups'
    ], function () {
        Route::get('', [GroupController::class, 'index'])->name('');
        Route::get('/index-json', [GroupController::class, 'indexJson'])->name('.indexJson');
        Route::get('/add', [GroupController::class, 'add'])->name('.add');
        Route::get('/edit/{id?}', [GroupController::class, 'add'])->name('.edit');
        Route::post('/store', [GroupController::class, 'store'])->name('.store');
        Route::delete('/delete/{id}', [GroupController::class, 'delete'])->name('.delete');
    });

    Route::group([
        'prefix' => 'customers',
        'as' => '.customers'
    ], function () {
        Route::get('', [CustomerController::class, 'index'])->name('');
        Route::get('/index-json', [CustomerController::class, 'indexJson'])->name('.indexJson');
        Route::get('/add', [CustomerController::class, 'add'])->name('.add');
        Route::get('/edit/{id?}', [CustomerController::class, 'add'])->name('.edit');
        Route::post('/store', [CustomerController::class, 'store'])->name('.store');
        Route::delete('/delete/{id}', [CustomerController::class, 'delete'])->name('.delete');
    });

    Route::group([
        'prefix' => 'entries',
        'as' => '.entries'
    ], function () {
        Route::get('', [EntryController::class, 'index'])->name('');
        Route::get('/index-json', [EntryController::class, 'indexJson'])->name('.indexJson');
        Route::get('/add', [EntryController::class, 'add'])->name('.add');
        Route::post('/store', [EntryController::class, 'store'])->name('.store');
        Route::get('/edit/{id?}', [EntryController::class, 'edit'])->name('.edit');

        Route::delete('/delete/{id}', [EntryController::class, 'delete'])->name('.delete');
    });

    Route::group([
        'prefix' => 'reports',
        'as' => '.reports'
    ], function () {
        Route::get('', [ReportController::class, 'index'])->name('');
        Route::get('/index-json', [ReportController::class, 'indexJson'])->name('.indexJson');
    });
});
