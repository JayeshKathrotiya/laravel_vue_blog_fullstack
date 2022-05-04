<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

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

// tags routes
Route::post('app/create_tag', [AdminController::class, 'addTag']);
Route::get('app/get_tags', [AdminController::class, 'getTag']);
Route::post('app/edit_tag', [AdminController::class, 'editTag']);
Route::post('app/delete_tag', [AdminController::class, 'deleteTag']);

// categories routes
Route::post('app/upload', [AdminController::class, 'upload']);
Route::post('app/delete_image', [AdminController::class, 'deleteImage']);
Route::post('app/create_category', [AdminController::class, 'addCategory']);
Route::get('app/get_category', [AdminController::class, 'getCategory']);
Route::post('app/edit_category', [AdminController::class, 'editCategory']);

Route::get('/', function () {
    return view('welcome');
});

Route::any('{slug}', function(){
    return view('welcome');
});