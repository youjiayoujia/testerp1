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

Route::resource('productSupplier', 'Product\SupplierController');
Route::resource('productRequire', 'Product\RequireController');
Route::resource('warehouse', 'WarehouseController');
Route::resource('warehousePosition', 'Warehouse\PositionController');

//物流需求路由
Route::resource('logisticsSupplier', 'Logistics\SupplierController');
Route::resource('logistics', 'LogisticsController');
Route::resource('logisticsCode', 'Logistics\CodeController');

//品类路由
Route::resource('catalog', 'CatalogController');
