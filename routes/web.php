<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ServiceRecordController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/equipment');

// crud zarizeni
Route::resource('equipment', EquipmentController::class);
Route::get('equipment/{equipment}/export', [EquipmentController::class, 'export'])->name('equipment.export');

// servisni zaznamy
Route::post('equipment/{equipment}/service-records', [ServiceRecordController::class, 'store'])->name('service-records.store');
Route::delete('service-records/{serviceRecord}', [ServiceRecordController::class, 'destroy'])->name('service-records.destroy');
