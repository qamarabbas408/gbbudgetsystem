<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SapUploadController;

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

// Route::get('/@{username}/{slug}', [PostController::class, 'show'])->name('posts.show');
