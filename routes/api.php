<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceLogsController;
use App\Http\Controllers\KategoriMenuController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::get('/all-product', [ProductController::class, 'productAll']);
Route::get('/all-category-product', [ProductController::class, 'categoryProductAll']);
Route::get('/kategori/{id_kategori}', [ProductController::class, 'byKategori']);
Route::get('/menu/{id_menu}', [ProductController::class, 'byIdMenu']);
Route::get('/pluck/{id_menu}', [ProductController::class, 'pluckToName']);
Route::get('/join', [ProductController::class, 'joinTable']);
Route::post('/order-details', [ProductController::class, 'orderDetails']);

Route::get('/order-by-invoice/{nomor_invoice}', [OrderController::class, 'orderByInvoice']);
Route::post('/orders', [OrderController::class, 'orders']);

Route::post('/device-logs', [DeviceLogsController::class, 'insert']);


// Admin API

Route::group(["prefix" => "admin"],function () {
    Route::post("/login",[AuthController::class,'login']);
    Route::middleware('auth:sanctum')->group(function() {
        Route::get("/user",[AuthController::class,"user"]);
        Route::post("/logout",[AuthController::class,'logout']);

        Route::get("/users/all",[UserController::class,"get_all"]);
        Route::post("/users/insert",[UserController::class,"insert"]);
        Route::post("/users/update/{id}",[UserController::class,"update"]);
        Route::post("/users/delete/{id}",[UserController::class,"delete"]);

        Route::get("/kategori_menu/all",[KategoriMenuController::class,"get_all"]);
        Route::post("/kategori_menu/insert",[KategoriMenuController::class,"insert"]);
        Route::post("/kategori_menu/update/{id}",[KategoriMenuController::class,"update"]);
        Route::post("/kategori_menu/delete/{id}",[KategoriMenuController::class,"delete"]);

        Route::get("/menu/all",[MenuController::class,"get_all"]);
        Route::post("/menu/insert",[MenuController::class,"insert"]);
        Route::post("/menu/update/{id}",[MenuController::class,"update"]);
        Route::post("/menu/delete/{id}",[MenuController::class,"delete"]);
    });
});
