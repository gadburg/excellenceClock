<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;

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
    //Route::resource('/empresas', EmpresaController::class);
    Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
    Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('/empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::post('/empresas/update', [EmpresaController::class, 'update'])->name('empresas.update');
    Route::delete('/empresas/{empresa}/delete', [EmpresaController::class, 'destroy'])->name('empresas.destroy');
   
    Route::get('/informes', function () {return Inertia::render('Informes/Informes');})->name('informes');
    Route::get('/usuarios-empresa',[UserController::class, 'obtenerUsuariosEmpresa'])->name('usuariosEmpresa');
    Route::get('/registro-usuario/{userId}/{fechaInicio}/{fechaFin}',[RegistroController::class, 'obtenerRegistrosRango'])->name('registroUsuario');

    Route::resource('/registros', RegistroController::class);
   
    //Route::delete('/empresas/borrar/{id}', [EmpresaController::class, 'borrar'])->name('empresas.borrar');
    Route::get('/mostrar-mes/{mes}/{anio}',[RegistroController::class, 'mostrarMes']);
    Route::get('/horas-rango/{fechaInicio}/{fechaFin}',[RegistroController::class, 'horasRango']);
});