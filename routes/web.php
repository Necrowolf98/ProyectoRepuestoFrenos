<?php

use App\Http\Controllers\MarcaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RepuestoFrenoController;
use App\Http\Controllers\VehiculoController;

Route::group(['middleware'=>['auth:sanctum']],function(){

    Route::get('/api/users', [UserController::class, 'index'])->middleware(['permission:users.index']);
    Route::post('/api/users', [UserController::class, 'store'])->middleware(['permission:users.create']);
    Route::get('/api/users/{user}', [UserController::class, 'show'])->middleware(['permission:users.show']);
    Route::put('/api/users/{user}', [UserController::class, 'update'])->middleware(['permission:users.edit']);
    Route::delete('/api/users/{user}', [UserController::class, 'destroy'])->middleware(['permission:users.delete']);
    Route::put('/api/users/updatepermissionsbyuser/{user}', [UserController::class, 'updatePermissionsByUser'])
    ->middleware(['permission:users.permissions']);


    Route::get('/api/roles', [RoleController::class, 'index'])->middleware(['permission:roles.index']);
    Route::get('/api/roles/create', [RoleController::class, 'create'])->middleware(['permission:roles.create']);
    Route::post('/api/roles', [RoleController::class, 'store'])->middleware(['permission:roles.create']);
    Route::get('/api/roles/{role}/edit', [RoleController::class, 'edit'])->middleware(['permission:roles.edit']);
    Route::put('/api/roles/{role}', [RoleController::class, 'update'])->middleware(['permission:roles.edit']);
    Route::get('/api/roles/{role}', [RoleController::class, 'destroy'])->middleware(['permission:roles.delete']);

    Route::get('/api/permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.index');
    Route::post('/api/permissions', [PermissionController::class, 'store'])->middleware('permission:permissions.create');
    Route::put('/api/permissions/{permission}', [PermissionController::class, 'update'])->middleware('permission:permissions.edit');
    Route::delete('/api/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:permissions.delete');

    Route::get('/api/marcas', [MarcaController::class, 'index'])->middleware('permission:marca.index');
    Route::post('/api/marcas', [MarcaController::class, 'store'])->middleware('permission:marca.create');
    Route::put('/api/marcas/{marca}', [MarcaController::class, 'update'])->middleware('permission:marca.edit');
    Route::delete('/api/marcas/{marca}', [MarcaController::class, 'destroy'])->middleware('permission:marca.delete');


    Route::get('/api/repuestofrenos', [RepuestoFrenoController::class, 'index'])->middleware('permission:repuesto.index');
    Route::post('/api/repuestofrenos', [RepuestoFrenoController::class, 'store'])->middleware('permission:repuesto.create');
    Route::put('/api/repuestofrenos/{repuestofreno}', [RepuestoFrenoController::class, 'update'])->middleware(['permission:repuesto.edit']);
    Route::delete('/api/repuestofrenos/{repuestofreno}', [RepuestoFrenoController::class, 'destroy'])->middleware(['permission:repuesto.delete']);

    Route::get('/api/vehiculos', [VehiculoController::class, 'index'])->middleware('permission:dashboard.index');
    Route::post('/api/vehiculos', [VehiculoController::class, 'store'])->middleware('permission:dashboard.create');
    Route::put('/api/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->middleware(['permission:dashboard.edit']);
    Route::delete('/api/vehiculos/{vehiculo}', [VehiculoController::class, 'destroy'])->middleware(['permission:dashboard.delete']);

    Route::get('/api/authpermissions', [\App\Http\Controllers\Auth\AuthController::class, 'authpermissions']);
    Route::post('/api/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
});


Route::get('/api/comprobarLogin', [\App\Http\Controllers\Auth\AuthController::class, 'comprobarLogin']);

Route::post('/api/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);

Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath')->where('optional', '.*');
