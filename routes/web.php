<?php

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

Route::get('/', 'HomeController@home')->name('home');
Route::get('/contact', 'HomeController@contact')->name('contact');
Route::resource('/posts', 'PostController');
Auth::routes();
    //->except(['destroy']);
    // ->only(['index', 'show', 'create', 'store', 'edit', 'update']);
