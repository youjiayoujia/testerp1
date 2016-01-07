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
//产品图片路由
Route::resource('productImage', 'Product\ImageController');
Route::resource('productSupplier', 'Product\SupplierController');
Route::resource('productRequire', 'Product\RequireController');
Route::resource('warehouse', 'WarehouseController');
Route::resource('warehousePosition', 'Warehouse\PositionController');

//品类路由
Route::resource('catalog', 'CatalogController');

//渠道路由
Route::resource('channel', 'ChannelController');

//渠道账号路由
Route::resource('channelAccount', 'Channel\AccountController');
