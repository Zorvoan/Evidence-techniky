<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ServiceRecordController;
use Illuminate\Support\Facades\Route;

// Úvodní stránka přesměruje rovnou na seznam zařízení.
Route::redirect('/', '/equipment');

// CRUD zařízení: index, create, store, show, edit, update, destroy.
Route::resource('equipment', EquipmentController::class);

// Export servisní historie jednoho zařízení do CSV.
Route::get('equipment/{equipment}/export', [EquipmentController::class, 'export'])
    ->name('equipment.export');

// Přidání servisního záznamu z detailu zařízení.
Route::post('equipment/{equipment}/service-records', [ServiceRecordController::class, 'store'])
    ->name('service-records.store');

// Smazání servisního záznamu.
Route::delete('service-records/{serviceRecord}', [ServiceRecordController::class, 'destroy'])
    ->name('service-records.destroy');
