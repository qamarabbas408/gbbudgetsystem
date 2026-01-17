<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SapUploadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DepartmentMappingController;

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

// Add ->name('dashboard') here
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/upload', [SapUploadController::class, 'index'])->name('sap.upload');
Route::post('/upload', [SapUploadController::class, 'store'])->name('sap.store');
Route::post('/sap/store-batch', [SapUploadController::class, 'storeBatch'])->name('sap.storeBatch');
// Route::post('/sap/store-batch', [SapUploadController::class, 'storeBatch'])->name('sap.storeBatch2');

Route::get('/reports/adp-summary', [ReportController::class, 'adpSummary'])->name('reports.adpSummary');
Route::get('/reports/sdg-summary', [ReportController::class, 'sdgSummary'])->name('reports.sdgSummary');

// Route::get('/@{username}/{slug}', [PostController::class, 'show'])->name('posts.show');


Route::get('/settings/mappings', [DepartmentMappingController::class, 'index'])->name('mappings.index');
Route::post('/settings/mappings', [DepartmentMappingController::class, 'store'])->name('mappings.store');
Route::post('/settings/mappings/sync', [DepartmentMappingController::class, 'syncAll'])->name('mappings.sync');
Route::patch('/settings/mappings/{id}', [DepartmentMappingController::class, 'update'])->name('mappings.update');
Route::delete('/settings/mappings/{id}', [DepartmentMappingController::class, 'destroy'])->name('mappings.destroy');