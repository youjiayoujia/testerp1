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

Route::get('test', 'TestController@test');
Route::get('test1/{url}', ['uses' => 'TestController@test1', 'as' => 'test1']);
Route::any('/', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

//产品图片路由
Route::resource('productImage', 'Product\ImageController');

//reported smissing  reportedMissingCreate
Route::post('reportedMissingCreate', 'product\ReportedMissingController@store');
Route::resource('reportedMissing', 'Product\ReportedMissingController');

Route::resource('brand', 'brandController');
Route::resource('catalog', 'CatalogController');
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

//入库
Route::resource('stockIn', 'Stock\InController');

//出库
Route::resource('stockOut', 'Stock\OutController');

//供货商
Route::resource('productSupplier', 'Product\SupplierController');

//选款需求
Route::resource('productRequire', 'Product\RequireController');

//仓库
Route::resource('warehouse', 'WarehouseController');

//库存调整
Route::get('stockAdjustment/adjustAdd',
    ['uses' => 'Stock\AdjustmentController@ajaxAdjustAdd', 'as' => 'stockAdjustment.adjustAdd']);
Route::get('stockAdjustment/check',
    ['uses' => 'Stock\AdjustmentController@ajaxCheck', 'as' => 'stockAdjustment.check']);
Route::resource('stockAdjustment', 'Stock\AdjustmentController');

//库位
Route::get('position/getPosition',
    ['uses' => 'Warehouse\PositionController@ajaxGetPosition', 'as' => 'position.getPosition']);
Route::resource('warehousePosition', 'Warehouse\PositionController');

//库存
Route::get('stock/createTaking', ['uses' => 'StockController@createTaking', 'as' => 'stock.createTaking']);
Route::get('stock/allotSku', ['uses' => 'StockController@ajaxAllotSku', 'as' => 'stock.allotSku']);
Route::get('stock/allotOutWarehouse',
    ['uses' => 'StockController@ajaxAllotOutWarehouse', 'as' => 'stock.allotOutWarehouse']);
Route::get('stock/getByPosition', ['uses' => 'StockController@ajaxGetByPosition', 'as' => 'stock.getByPosition']);
Route::get('stock/allotPosition', ['uses' => 'StockController@ajaxAllotPosition', 'as' => 'stock.allotPosition']);
Route::get('stock/getMessage', ['uses' => 'StockController@ajaxGetMessage', 'as' => 'stock.getMessage']);
Route::get('stock/allotPosition', ['uses' => 'StockController@ajaxAllotPosition', 'as' => 'stock.allotPosition']);

//采购条目
Route::any('purchaseItem/changeStatus', 'Purchase\PurchaseItemController@changeStatus');
Route::any('purchaseItem/form_postCoding', 'Purchase\PurchaseItemController@form_postCoding');
Route::any('purchaseItem/supplierCost', 'Purchase\PurchaseItemController@supplierCost');
Route::any('purchaseItem/cancelThisItem/{id}', 'Purchase\PurchaseItemController@cancelThisItem');
Route::any('/purchaseItem/activeCreate', 'Purchase\PurchaseItemController@activeCreate');
Route::resource('purchaseItem', 'Purchase\PurchaseItemController');
Route::any('/addPurchaseOrder', 'Purchase\PurchaseItemController@addPurchaseOrder');

//采购单
Route::any('purchaseOrder/examinePurchaseOrder', 'Purchase\purchaseOrderController@examinePurchaseOrder');
Route::any('purchaseOrder/purchaseOrderSupplier', 'Purchase\purchaseOrderController@purchaseOrderSupplier');
Route::any('purchaseOrder/checkProductItems', 'Purchase\purchaseOrderController@checkProductItems');
Route::any('purchaseOrder/checkedPurchaseItem', 'Purchase\purchaseOrderController@checkedPurchaseItem');
Route::any('purchaseOrder/excelOut/{id}', 'Purchase\purchaseOrderController@excelOut');
Route::any('purchaseOrder/printOrder/{id}', 'Purchase\purchaseOrderController@printOrder');
Route::any('purchaseOrder/cancelOrder/{id}', 'Purchase\purchaseOrderController@cancelOrder');
Route::resource('purchaseOrder', 'Purchase\purchaseOrderController');

//采购列表
Route::any('purchaseList/stockIn/{id}', 'Purchase\PurchaseListController@stockIn');
Route::any('purchaseList/generateDarCode/{id}', 'Purchase\PurchaseListController@generateDarCode');
Route::any('purchaseList/activeChange/{id}', 'Purchase\PurchaseListController@activeChange');
Route::any('purchaseList/updateActive/{id}', 'Purchase\PurchaseListController@updateActive');
Route::resource('purchaseList', 'Purchase\PurchaseListController');

/**
 * stock controller route
 */
Route::get('allotsku', ['uses' => 'StockController@ajaxAllotSku', 'as' => 'allotsku']);
Route::get('allotoutwarehouse', ['uses' => 'StockController@ajaxAllotOutWarehouse', 'as' => 'allotoutwarehouse']);
Route::get('getbyposition', ['uses' => 'StockController@ajaxGetByPosition', 'as' => 'getbyposition']);
Route::get('getmessage', ['uses' => 'StockController@ajaxGetMessage', 'as' => 'getmessage']);
Route::get('allotposition', ['uses' => 'StockController@ajaxAllotPosition', 'as' => 'allotposition']);

Route::resource('stock', 'StockController');


//品类路由
Route::resource('catalog', 'CatalogController');

//item路由
Route::resource('item', 'ItemController');

//渠道路由
Route::resource('channel', 'ChannelController');

//渠道账号路由
Route::resource('channelAccount', 'Channel\AccountController');


//库存调拨
Route::post('allotment/checkResult/{id}',
    ['uses' => 'Stock\AllotmentController@checkResult', 'as' => 'allotment.checkResult']);
Route::get('allotment/over/{id}', ['uses' => 'Stock\AllotmentController@allotmentOver', 'as' => 'allotment.over']);
Route::post('allotment/getLogistics/{id}',
    ['uses' => 'Stock\AllotmentController@getLogistics', 'as' => 'allotment.getLogistics']);
Route::get('allotment/new', ['uses' => 'Stock\AllotmentController@ajaxAllotmentNew', 'as' => 'allotment.new']);
Route::get('allotment/checkout',
    ['uses' => 'Stock\AllotmentController@checkout', 'as' => 'allotment.checkout']);
Route::get('allotment/add', ['uses' => 'Stock\AllotmentController@ajaxAllotmentAdd', 'as' => 'allotment.add']);
Route::post('allotment/checkformUpdate/{id}',
    ['uses' => 'Stock\AllotmentController@checkformupdate', 'as' => 'allotment.checkformUpdate']);
Route::get('allotment/checkform/{id}',
    ['uses' => 'Stock\AllotmentController@checkform', 'as' => 'allotment.checkform']);
Route::get('allotment/pick', ['uses' => 'Stock\AllotmentController@allotmentpick', 'as' => 'allotment.pick']);
Route::get('allotment/check/{id}', ['uses' => 'Stock\AllotmentController@allotmentCheck', 'as' => 'allotment.check']);
Route::resource('stockAllotment', 'Stock\AllotmentController');

//库存盘点
Route::get('quantitycheck', ['uses' => 'Stock\TakingController@ajaxQuantityCheck', 'as' => 'quantitycheck']);
Route::get('takingcheck', ['uses' => 'Stock\TakingController@ajaxTakingcheck', 'as' => 'takingcheck']);
Route::post('takingupdate', ['uses' => 'Stock\TakingController@takingUpdate', 'as' => 'takingupdate']);
Route::get('edittaking', ['uses' => 'Stock\TakingController@editTaking', 'as' => 'edittaking']);
Route::resource('stockTaking', 'Stock\TakingController');

//盘点调整
Route::post('takingadjustmentcheckresult',
    ['uses' => 'Stock\TakingAdjustmentController@takingAdjustmentCheckResult', 'as' => 'takingadjustmentcheckresult']);
Route::get('takingadjustmentcheck/{id}',
    ['uses' => 'Stock\TakingAdjustmentController@takingAdjustmentCheck', 'as' => 'takingadjustmentcheck']);
Route::resource('stockTakingAdjustment', 'Stock\TakingAdjustmentController');

//物流路由
Route::resource('logistics', 'LogisticsController');
Route::resource('logisticsSupplier', 'Logistics\SupplierController');
Route::resource('logisticsCode', 'Logistics\CodeController');
Route::resource('logisticsZone', 'Logistics\ZoneController');
Route::get('zone', ['uses' => 'LogisticsController@zone', 'as' => 'zone']);
Route::get('country', ['uses' => 'Logistics\ZoneController@country', 'as' => 'country']);
Route::get('zoneShipping', ['uses' => 'Logistics\ZoneController@zoneShipping', 'as' => 'zoneShipping']);
Route::get('count', ['uses' => 'Logistics\ZoneController@count', 'as' => 'count']);
Route::get('countExpress/{id}', ['uses' => 'Logistics\ZoneController@countExpress', 'as' => 'countExpress']);
Route::get('countPacket/{id}', ['uses' => 'Logistics\ZoneController@countPacket', 'as' => 'countPacket']);
Route::get('batchAddTrCode/{logistics_id}',
    ['uses' => 'Logistics\CodeController@batchAddTrCode', 'as' => 'batchAddTrCode']);
Route::post('logisticsCodeFn', ['uses' => 'Logistics\CodeController@batchAddTrCodeFn', 'as' => 'logisticsCodeFn']);
Route::get('scanAddTrCode/{logistics_id}',
    ['uses' => 'Logistics\CodeController@scanAddTrCode', 'as' => 'scanAddTrCode']);
Route::post('scanAddTrCodeFn', ['uses' => 'Logistics\CodeController@scanAddTrCodeFn', 'as' => 'scanAddTrCodeFn']);

//拣货路由
Route::post('pickList/inboxStore/{id}', ['uses' => 'PickListController@inboxStore', 'as' => 'pickList.inboxStore']);
Route::post('pickList/createPickStore',
    ['uses' => 'PickListController@createPickStore', 'as' => 'pickList.createPickStore']);
Route::get('pickList/createPick', ['uses' => 'PickListController@createPick', 'as' => 'pickList.createPick']);
Route::get('pickList/inboxResult', ['uses' => 'PickListController@ajaxInboxResult', 'as' => 'pickList.inboxResult']);
Route::get('pickList/inbox/{id}', ['uses' => 'PickListController@inbox', 'as' => 'pickList.inbox']);
Route::get('pickList/packageItemUpdate',
    ['uses' => 'PickListController@ajaxPackageItemUpdate', 'as' => 'pickList.packageItemUpdate']);
Route::post('pickList/packageStore/{id}',
    ['uses' => 'PickListController@packageStore', 'as' => 'pickList.packageStore']);
Route::get('pickList/package/{id}', ['uses' => 'PickListController@pickListPackage', 'as' => 'pickList.package']);
Route::get('pickList/print/{id}', ['uses' => 'PickListController@printPickList', 'as' => 'pickList.print']);
Route::get('pickList/type', ['uses' => 'PickListController@ajaxType', 'as' => 'pickList.type']);
Route::resource('pickList', 'PickListController');

//产品管理路由
Route::any('product/getCatalogProperty', 'ProductController@getCatalogProperty');
Route::get('examine', ['uses' => 'ProductController@examine', 'as' => 'examine']);
Route::get('examine', ['uses' => 'ProductController@examine', 'as' => 'examine']);
Route::get('choseShop', ['uses' => 'ProductController@choseShop', 'as' => 'choseShop']);
Route::resource('product', 'ProductController');


//产品渠道
Route::any('beChosed', ['uses' => 'Product\SelectProductController@beChosed', 'as' => 'beChosed']);
//Route::resource('amazonProduct', 'Product\Channel\AmazonController');
Route::resource('EditProduct', 'Product\EditProductController');
Route::resource('SelectProduct', 'Product\SelectProductController');
Route::get('amazonProductEditImage',
    ['uses' => 'Product\Channel\AmazonController@amazonProductEditImage', 'as' => 'amazonProductEditImage']);
Route::post('amazonProductUpdateImage',
    ['uses' => 'Product\Channel\AmazonController@amazonProductUpdateImage', 'as' => 'amazonProductUpdateImage']);
Route::get('examineAmazonProduct',
    ['uses' => 'Product\Channel\AmazonController@examineAmazonProduct', 'as' => 'examineAmazonProduct']);
Route::get('cancelExamineAmazonProduct',
    ['uses' => 'Product\Channel\AmazonController@cancelExamineAmazonProduct', 'as' => 'cancelExamineAmazonProduct']);


//订单管理路由
Route::resource('order', 'OrderController');
Route::resource('orderItem', 'Order\ItemController');
Route::get('orderAdd', ['uses' => 'OrderController@ajaxOrderAdd', 'as' => 'orderAdd']);

//包裹管理路由
Route::post('package/feeStore', ['uses' => 'PackageController@feeStore', 'as' => 'package.feeStore']);
Route::get('package/manualLogistic/{id}',
    ['uses' => 'PackageController@manualLogistic', 'as' => 'package.manualLogistic']);
Route::get('package/ajaxPackageSend',
    ['uses' => 'PackageController@ajaxPackageSend', 'as' => 'package.ajaxPackageSend']);
Route::any('package/ajaxGetOrder', ['uses' => 'PackageController@ajaxGetOrder', 'as' => 'package.ajaxGetOrder']);
Route::resource('package', 'PackageController');

Route::get('account', ['uses' => 'OrderController@account', 'as' => 'account']);
Route::get('getMsg', ['uses' => 'OrderController@getMsg', 'as' => 'getMsg']);
Route::get('getChoiesOrder', ['uses' => 'OrderController@getChoiesOrder', 'as' => 'getChoiesOrder']);
Route::get('getCode', ['uses' => 'OrderController@getCode', 'as' => 'getCode']);
Route::get('getAliExpressOrder', ['uses' => 'OrderController@getAliExpressOrder', 'as' => 'getAliExpressOrder']);