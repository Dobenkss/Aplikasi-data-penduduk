<?php

use App\Http\Controllers\Export_controller;
use App\Http\Controllers\Kabupaten_controller;
use App\Http\Controllers\Penduduk_controller;
use App\Http\Controllers\Provinsi_controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//DASHBOARD
Route::get('/', function () {
    return view('welcome');
});

//PROVINSI
Route::get('/admin-provinsi', [Provinsi_controller::class, 'index']);
Route::get('/admin-provinsi-create', [Provinsi_controller::class, 'create']);
Route::post('/admin-provinsi-store', [Provinsi_controller::class, 'store']);
Route::get('/admin-provinsi-edit/{edit}', [Provinsi_controller::class, 'edit']);
Route::put('/admin-provinsi-update/{edit}', [Provinsi_controller::class, 'update']);
Route::delete('/admin-provinsi-destroy/{destroy}', [Provinsi_controller::class, 'destroy']);

//KABUPATEN
Route::get('/admin-kabupaten', [Kabupaten_controller::class, 'index']);
Route::get('/admin-kabupaten-create', [Kabupaten_controller::class, 'create']);
Route::post('/admin-kabupaten-store', [Kabupaten_controller::class, 'store']);
Route::get('/admin-kabupaten-edit/{edit}', [Kabupaten_controller::class, 'edit']);
Route::put('/admin-kabupaten-update/{edit}', [Kabupaten_controller::class, 'update']);
Route::delete('/admin-kabupaten-destroy/{destroy}', [Kabupaten_controller::class, 'destroy']);

//PENDUDUK
Route::get('/admin-penduduk', [Penduduk_controller::class, 'index']);
Route::get('/admin-penduduk-create', [Penduduk_controller::class, 'create']);
Route::post('/admin-penduduk-store', [Penduduk_controller::class, 'store']);
Route::get('/get-kabupaten/{provinsiId}', [Penduduk_controller::class, 'getKabupaten']);
Route::get('/admin-penduduk-edit/{edit}', [Penduduk_controller::class, 'edit']);
Route::put('/admin-penduduk-update/{edit}', [Penduduk_controller::class, 'update']);
Route::delete('/admin-penduduk-destroy/{destroy}', [Penduduk_controller::class, 'destroy']);

//EXPORT
Route::get('/admin-export', [Penduduk_controller::class, 'indexExport']);
Route::get('/admin-export-excel', [Penduduk_controller::class, 'exportExcel'])->name('export.excel');
Route::get('/admin-export-print', [Penduduk_controller::class, 'printData'])->name('export.print');