<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SapUploadController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DepartmentMappingController;
use App\Http\Controllers\AdpFormulationController;

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

// Route::get('/sap/history', function () {
//     return view('sapuploads.list');
// })->name('sap.list');


Route::get('/upload', [SapUploadController::class, 'index'])->name('sap.upload');
Route::post('/upload', [SapUploadController::class, 'store'])->name('sap.store');
Route::post('/sap/store-batch', [SapUploadController::class, 'storeBatch'])->name('sap.storeBatch');
// Route::post('/sap/store-batch', [SapUploadController::class, 'storeBatch'])->name('sap.storeBatch2');

Route::get('/reports/adp-summary', [ReportController::class, 'adpSummary'])->name('reports.adpSummary');
Route::get('/reports/sdg-summary', [ReportController::class, 'sdgSummary'])->name('reports.sdgSummary');
Route::get('/reports/sector-summary', [ReportController::class, 'sectorSummary'])->name('reports.sectorSummary');
Route::get('/reports/sector-dept-analysis', [ReportController::class, 'sectorDeptAnalysis'])->name('reports.sectorDeptAnalysis');
// Route::get('/@{username}/{slug}', [PostController::class, 'show'])->name('posts.show');


Route::get('/settings/mappings', [DepartmentMappingController::class, 'index'])->name('mappings.index');
Route::post('/settings/mappings', [DepartmentMappingController::class, 'store'])->name('mappings.store');
Route::post('/settings/mappings/sync', [DepartmentMappingController::class, 'syncAll'])->name('mappings.sync');
Route::patch('/settings/mappings/{id}', [DepartmentMappingController::class, 'update'])->name('mappings.update');
Route::delete('/settings/mappings/{id}', [DepartmentMappingController::class, 'destroy'])->name('mappings.destroy');

Route::get('/sap/history', [SapUploadController::class, 'list'])->name('sap.list');
Route::post('/sap/history/{id}/activate', [SapUploadController::class, 'activate'])->name('sap.activate');
Route::delete('/sap/history/{id}', [SapUploadController::class, 'destroy'])->name('sap.destroy');

Route::get('/reports/export/{format}', [ReportController::class, 'exportSectorDept'])->name('reports.export');

Route::get('/adp/formulation', function () {
    return view('adp.formulation');
})->name('adp.formulation');
Route::post('/adp/store-formulation', [AdpFormulationController::class, 'store'])->name('adp.storeFormulation');
Route::get('/adp/upload',function () {
     return view('adp.upload');
});