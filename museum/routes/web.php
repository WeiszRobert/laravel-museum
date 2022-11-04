<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Redirect;
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
Route::get('/', function () {
    return Redirect::route('items.index');
});

Route::resources([
    'items' => ItemController::class,
    'labels' => LabelController::class,
    'comments' => CommentController::class
]);

Route::post('items/{item:id}/comments', [CommentController::class, 'store']);
//Route::delete('items/{item:id}/comments/{comment:id}', [CommentController::class, 'destroy']);

/*
Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', function () {
    return view('posts.index');
});

Route::get('/posts/create', function () {
    return view('posts.create');
});

Route::get('/posts/x', function () {
    return view('posts.show');
});

Route::get('/posts/x/edit', function () {
    return view('posts.edit');
});

// -----------------------------------------

Route::get('/categories/create', function () {
    return view('categories.create');
});

Route::get('/categories/x', function () {
    return view('categories.show');
});
*/
// -----------------------------------------

Auth::routes();
