<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::any('/', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);
Route::any('productUpload', 'product\ImageController@store');
Route::any('productUpdate', 'product\ImageController@update');
//Route::any('productZipUpload', 'Product\ImageController@zipUpload');

Route::resource('product', 'productController');
Route::get('product/addimage/{id}', 'productController@addimage');


Route::resource('brand', 'brandController');
Route::resource('catalog', 'CatalogController');
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::resource('productImage', 'Product\ImageController');
Route::resource('productSupplier', 'Product\SupplierController');
Route::resource('productRequire', 'Product\RequireController');
Route::resource('warehouse', 'WarehouseController');
Route::resource('warehousePosition', 'Warehouse\PositionController');

