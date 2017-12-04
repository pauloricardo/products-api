<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'api\RequestAuthentication@login');
Route::get('/logout', 'api\RequestAuthentication@logout');
Route::post('/login/refresh', 'api\RequestAuthentication@refresh');
    Route::get('/', function () {
        return response()->json(['message' => 'Jobs API', 'status' => 'Connected']);
    });
    Route::post('products/upload-image', 'api\RequestProducts@uploadImage');
    Route::post('products/create', 'api\RequestProducts@create');
    Route::get('products/find/{product}', 'api\RequestProducts@find');
    Route::post('products/edit/{product}', 'api\RequestProducts@edit');
    Route::delete('products/destroy/{product}', 'api\RequestProducts@destroy');
    Route::post('products/upload-csv', 'api\RequestProducts@uploadCsv');
    Route::resource('products', 'api\RequestProducts');
    Route::resource('product-categories', 'api\RequestProductCategories')->middleware('auth:api');

Route::get('/', function () {
    return redirect('api');
});

