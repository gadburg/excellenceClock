<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\EmpresaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    //rutas
    //Route::post('/reloj', [RegistroController::class, 'store'])->name('dashboard');
    //Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
    //Route::get('/empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
    //Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::resource('/empresas', EmpresaController::class);
});