<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\VentaController;

// Ruta raíz redirige al dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// Rutas protegidas — requieren login
Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Módulo de órdenes (CRUD completo)
    Route::resource('ordenes', OrdenController::class)
        ->parameters(['ordenes' => 'orden']);

    // Cambiar estado de una orden
    Route::patch('/ordenes/{orden}/estado', [OrdenController::class, 'cambiarEstado'])
        ->name('ordenes.estado');

    // Asociar/quitar repuestos de una orden
    Route::post('/ordenes/{orden}/repuestos', [OrdenController::class, 'agregarRepuesto'])
        ->name('ordenes.repuestos.add');
    Route::delete('/ordenes/{orden}/repuestos/{repuesto}', [OrdenController::class, 'quitarRepuesto'])
        ->name('ordenes.repuestos.remove');

    // Módulo de pagos (solo se crean desde la vista de una orden)
    Route::post('/ordenes/{orden}/pagos', [PagoController::class, 'store'])
        ->name('pagos.store');

    // Buscar cliente por DNI — para autocompletar en el formulario de orden
    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])
        ->name('clientes.buscar');

    // Módulo de clientes
    Route::resource('clientes', ClienteController::class)
        ->parameters(['clientes' => 'cliente']);

    // Módulo de ventas
    Route::resource('ventas', VentaController::class)
        ->parameters(['ventas' => 'venta']);


    // Módulo de inventario
    Route::resource('repuestos', RepuestoController::class)
        ->parameters(['repuestos' => 'repuesto']);
});

// Rutas del portal QR — GET muestra formulario, POST valida DNI
Route::get('/consulta/{token}',  [ConsultaController::class, 'show'])->name('consulta.show');
Route::post('/consulta/{token}', [ConsultaController::class, 'show'])->name('consulta.verificar');

// Rutas de autenticación generadas por Breeze
require __DIR__ . '/auth.php';
