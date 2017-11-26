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

Route::get('/', function () {
    return view('dashboard.index');
});

Auth::routes();
//Route::get('/products/list', 'ProductsController@list');
//Route::get('/products/{products}/find', 'ProductsController@find');
//Route::get('/products/list-categories', 'ProductsController@listCategories');
//Route::get('/dashboard', 'DashboardController@index')->name('home');
//Route::resource('/dashboard/products', 'Dashboard\ProductsController', ['except' => ['edit', 'show']]);
