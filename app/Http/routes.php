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

Route::resource('supplier', 'Product\SupplierController');
Route::resource('require', 'Product\RequireController');
Route::resource('warehouse', 'WarehouseController');
Route::resource('position', 'Warehouse\PositionController');
Route::resource('logisticsSupplier', 'Logistics\SupplierController');
Route::resource('logistics', 'LogisticsController');

