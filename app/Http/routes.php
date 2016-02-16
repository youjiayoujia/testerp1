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
  
Route::get('test','TestController@test');
Route::get('test1','TestController@test1');
Route::any('/', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);
//产品图片路由
Route::resource('productImage', 'Product\ImageController');

//reported smissing  reportedMissingCreate
Route::post('reportedMissingCreate', 'product\ReportedMissingController@store');
Route::resource('reportedMissing', 'Product\ReportedMissingController');


Route::resource('product', 'productController');

Route::resource('brand', 'brandController');
Route::resource('catalog', 'CatalogController');
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

/**
 *  InController  controller  route
 */
Route::get('getitemid', ['uses' => 'Stock\InController@getItemId', 'as'=>'getitemid']);
Route::get('getposition', ['uses' => 'Stock\InController@ajaxGetPosition', 'as'=>'getposition']);
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
Route::get('check', ['uses'=>'Stock\AdjustmentController@ajaxCheck', 'as'=>'check']);
Route::resource('stockAdjustment', 'Stock\AdjustmentController');

/**
 * warehousePosition controller route
 */
Route::get('getposition', ['uses' => 'Warehouse\PositionController@ajaxGetPosition', 'as'=>'getposition']);
Route::resource('warehousePosition', 'Warehouse\PositionController');

/**
 * stock controller route
 */
Route::get('allotsku', ['uses'=>'StockController@ajaxAllotSku', 'as'=>'allotsku']);
Route::get('allotoutwarehouse', ['uses'=>'StockController@ajaxAllotOutWarehouse', 'as'=>'allotoutwarehouse']);
Route::get('getbyposition', ['uses'=>'StockController@ajaxGetByPosition', 'as'=>'getbyposition']);
Route::get('getmessage', ['uses'=>'StockController@ajaxGetMessage', 'as'=>'getmessage']);
Route::get('allotposition', ['uses'=>'StockController@ajaxAllotPosition','as'=>'allotposition']);
Route::resource('stock', 'StockController');

//品类路由
Route::resource('catalog', 'CatalogController');

//渠道路由
Route::resource('channel', 'ChannelController');

//渠道账号路由
Route::resource('channelAccount', 'Channel\AccountController');

/**
 *  stock allotment route
 */
Route::post('checkformupdate/{id}', ['uses'=>'Stock\AllotmentController@checkformupdate', 'as'=>'checkformupdate']);
Route::get('checkform/{id}', ['uses'=>'Stock\AllotmentController@checkform', 'as'=>'checkform']);
Route::get('allotmentpick', ['uses'=>'Stock\AllotmentController@allotmentpick', 'as'=>'allotmentpick']);
Route::get('allotmentcheck', ['uses' => 'Stock\AllotmentController@ajaxAllotmentcheck', 'as'=>'allotmentcheck']);
Route::resource('stockAllotment', 'Stock\AllotmentController');

