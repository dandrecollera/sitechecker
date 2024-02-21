<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FunctionController;

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

Route::get('/', [FunctionController::class, 'index'])->name('index');
Route::post('url_add', [FunctionController::class, 'addSite'])->name('url_add');
Route::post('url_edit', [FunctionController::class, 'editSite'])->name('url_edit');
Route::get('url_del', [FunctionController::class, 'deleteSite'])->name('url_delete');
