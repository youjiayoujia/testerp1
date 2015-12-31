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

/**
 *  InController  controller  route
 */
Route::get('getitemid', ['uses' => 'Stock\InController@getItemId', 'as'=>'getitemid']);
Route::get('getposition', ['uses' => 'Stock\InController@getPosition', 'as'=>'getposition']);
Route::resource('stockIn', 'Stock\InController');

/**
 * OutController controller route
 */
Route::resource('stockOut', 'Stock\OutController');

/**
 * productSupplier controller route
 */
Route::resource('productSupplier', 'Product\SupplierController');

/**
 * productRequire controller route
 */
Route::resource('productRequire', 'Product\RequireController');

/**
 * warehouse controller route
 */
Route::resource('warehouse', 'WarehouseController');

/**
 * stockAdjustment controller route
 */
Route::resource('stockAdjustment', 'Stock\AdjustmentController');

/**
 * warehousePosition controller route
 */
Route::resource('warehousePosition', 'Warehouse\PositionController');

/**
 * stock controller route
 */
Route::get('getunitcost', ['uses'=>'StockController@getUnitCost','as'=>'getunitcost']);
Route::resource('stock', 'StockController');






