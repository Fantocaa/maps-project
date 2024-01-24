<?php

use App\Http\Controllers\MdMapsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([])->group(function () {
    Route::resource('maps', MdMapsController::class);

    Route::post('/maps/edit/{id}/', [MdMapsController::class, 'update_maps'])->name('update_form_maps');

    Route::delete('/maps/delete/{id}/', [MdMapsController::class, 'delete_maps'])->name('delete_form_maps');

    Route::get('/role', [MdMapsController::class, 'has_role'])->name('has_role');
});
