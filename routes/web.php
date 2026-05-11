<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\ReporteController;

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showLogin'])->name('login');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/registro', [AuthController::class, 'showRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registro'])->name('registro.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'admin'])
            ->name('dashboard');

        // Propiedades
        Route::get('/propiedades', [PropiedadController::class, 'index'])
            ->name('propiedades');

        Route::post('/propiedades', [PropiedadController::class, 'store'])
            ->name('propiedades.store');

        Route::get('/buscar', [PropiedadController::class, 'buscarAdmin'])
            ->name('buscar');

        Route::put('/propiedades/{propiedad}', [PropiedadController::class, 'update'])
            ->name('propiedades.update');

        Route::delete('/propiedades/{propiedad}', [PropiedadController::class, 'destroy'])
            ->name('propiedades.destroy');

        // Usuarios
        Route::get('/usuarios', [UsuarioController::class, 'index'])
            ->name('usuarios');

        Route::post('/usuarios', [UsuarioController::class, 'store'])
            ->name('usuarios.store');

        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])
            ->name('usuarios.update');

        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])
            ->name('usuarios.destroy');

        // Reportes
        Route::get('/reportes', [ReporteController::class, 'index'])
            ->name('reportes');
    });

/*
|--------------------------------------------------------------------------
| AGENTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:agente,administrador'])
    ->prefix('agente')
    ->name('agente.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'agente'])
            ->name('dashboard');

        Route::get('/propiedades', [PropiedadController::class, 'index'])
            ->name('propiedades');

        Route::get('/buscar', [PropiedadController::class, 'buscarAgente'])
            ->name('buscar');

        Route::post('/propiedades', [PropiedadController::class, 'store'])
            ->name('propiedades.store');

        Route::put('/propiedades/{propiedad}', [PropiedadController::class, 'update'])
            ->name('propiedades.update');

        Route::delete('/propiedades/{propiedad}', [PropiedadController::class, 'destroy'])
            ->name('propiedades.destroy');

        Route::get('/visitas', [SolicitudController::class, 'visitasAgente'])
            ->name('visitas');

        Route::put('/visitas/{solicitud}/estado', [SolicitudController::class, 'actualizarEstado'])
            ->name('visitas.estado');

        Route::get('/clientes', [SolicitudController::class, 'clientesAgente'])
            ->name('clientes');
    });

/*
|--------------------------------------------------------------------------
| ASISTENTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:asistente,administrador'])
    ->prefix('asistente')
    ->name('asistente.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'asistente'])
            ->name('dashboard');

        Route::get('/buscar', [PropiedadController::class, 'buscarAsistente'])
            ->name('buscar');

        Route::get('/visitas', [SolicitudController::class, 'visitasAsistente'])
            ->name('visitas');

        Route::put('/visitas/{id}/estado',
            [SolicitudController::class, 'cambiarEstado'])
            ->name('visitas.estado');

        Route::get('/reportes', [ReporteController::class, 'index'])
            ->name('reportes');
    });

/*
|--------------------------------------------------------------------------
| CLIENTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('cliente')
    ->name('cliente.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'cliente'])
            ->name('dashboard');

        Route::get('/propiedades', [PropiedadController::class, 'disponibles'])
            ->name('propiedades');

        Route::get('/propiedades/{propiedad}', [PropiedadController::class, 'detalle'])
            ->name('propiedades.detalle');

        // CU8: Buscar propiedades
        Route::get('/buscar', [PropiedadController::class, 'buscar'])
            ->name('buscar');

        Route::post('/solicitudes', [SolicitudController::class, 'store'])
            ->name('solicitudes.store');

        Route::patch('/solicitudes/{id}/cancelar', [SolicitudController::class, 'cancelar'])
            ->name('solicitudes.cancelar');

        Route::get('/mis-solicitudes', [SolicitudController::class, 'misSolicitudes'])
            ->name('solicitudes');
    });

/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/perfil', [UsuarioController::class, 'perfil'])
        ->name('perfil');

    Route::put('/perfil', [UsuarioController::class, 'actualizarPerfil'])
        ->name('perfil.update');
});