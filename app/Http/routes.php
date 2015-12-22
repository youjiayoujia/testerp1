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

Route::resource('product', 'PoductController');
Route::resource('productSupplier', 'product\ProductSupplierController');
Route::resource('productRequire', 'product\ProductRequireController');
Route::resource('warehouse', 'warehouse\WarehouseController');
Route::resource('warehousePosition', 'warehouse\warehousePositionController');
Route::resource('catalog', 'CatalogController');
Route::resource('itemin', 'item\IteminController');
Route::resource('itemout', 'item\ItemoutController');
