<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ReservacionController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta POST protegida por CSRF para el chatbot
Route::post('/api/chatbot', [ChatbotController::class, 'responder']);


// Vista de reservaciones guardadas
Route::get('/reservaciones', [ReservacionController::class, 'index']);

// Ruta de prueba de logs
Route::get('/test-log', function () {
    \Log::info('✅ Log funcionando desde ruta');
    return 'Revisa storage/logs/laravel.log';
});


Route::get('/admin/reservaciones', [ReservacionController::class, 'vistaAdmin'])->name('admin.reservaciones');
