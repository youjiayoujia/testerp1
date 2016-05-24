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

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

//国家
Route::resource('countries', 'CountriesController');

//国家分类
Route::resource('countriesSort', 'CountriesSortController');

//采购条目
Route::group(['middleware' => 'auth'], function () {
    Route::get('test', 'TestController@test');
    Route::resource('countries', 'CountriesController');

    //3宝package
    Route::resource('bao3Package', 'Bao3PackageController');

    Route::any('/', ['as' => 'dashboard.index', 'uses' => 'PackageController@flow']);
//    Route::any('/', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

    //产品图片路由
    Route::resource('productImage', 'Product\ImageController');

    //reported smissing  reportedMissingCreate
    Route::post('reportedMissingCreate', 'product\ReportedMissingController@store');
    Route::resource('reportedMissing', 'Product\ReportedMissingController');

    //包装限制
    Route::resource('wrapLimits', 'WrapLimitsController');

    Route::any('catalog/checkName', ['uses' => 'CatalogController@checkName', 'as' => 'checkName']);

    //汇率
    Route::resource('currency', 'CurrencyController');

    //关帐s
    Route::resource('stockShut', 'Stock\ShutController');

    //hold库存
    Route::resource('stockHold', 'Stock\HoldController');

    //出库
    Route::resource('stockUnhold', 'Stock\UnholdController');

    //入库
    Route::resource('stockIn', 'Stock\InController');

    //出库
    Route::resource('stockOut', 'Stock\OutController');

    //供货商变更历史
    Route::resource('supplierChangeHistory', 'Product\SupplierChangeHistoryController');

    //供货商评级
    Route::resource('supplierLevel', 'Product\SupplierLevelController');

    //供货商
    Route::post('productSupplier/levelStore',
        ['uses' => 'Product\SupplierController@levelStore', 'as' => 'productSupplier.levelStore']);
    Route::get('productSupplier/createLevel',
        ['uses' => 'Product\SupplierController@createLevel', 'as' => 'productSupplier.createLevel']);
    Route::resource('productSupplier', 'Product\SupplierController');

    //选款需求
    Route::get('productRequire/ajaxQuantityProcess',
        ['uses' => 'Product\RequireController@ajaxQuantityProcess', 'as' => 'productRequire.ajaxQuantityProcess']);
    Route::get('productRequire/ajaxProcess',
        ['uses' => 'Product\RequireController@ajaxProcess', 'as' => 'productRequire.ajaxProcess']);
    Route::resource('productRequire', 'Product\RequireController');
    //通关报关
    Route::post('customsClearance/exportProduct',
        ['uses' => 'CustomsClearanceController@exportProduct', 'as' => 'customsClearance.exportProduct']);
    Route::get('customsClearance/exportFailModel',
        ['uses' => 'CustomsClearanceController@exportFailModel', 'as' => 'customsClearance.exportFailModel']);
    Route::get('customsClearance/exportFailItem',
        ['uses' => 'CustomsClearanceController@exportFailItem', 'as' => 'customsClearance.exportFailItem']);
    Route::post('customsClearance/exportNXB',
        ['uses' => 'CustomsClearanceController@exportNXB', 'as' => 'customsClearance.exportNXB']);
    Route::post('customsClearance/exportEUB',
        ['uses' => 'CustomsClearanceController@exportEUB', 'as' => 'customsClearance.exportEUB']);
    Route::post('customsClearance/exportEUBWeight',
        ['uses' => 'CustomsClearanceController@exportEUBWeight', 'as' => 'customsClearance.exportEUBWeight']);

    Route::post('customsClearance/exportProductZY',
        ['uses' => 'CustomsClearanceController@exportProductZY', 'as' => 'customsClearance.exportProductZY']);
    Route::post('customsClearance/exportProductEUB',
        ['uses' => 'CustomsClearanceController@exportProductEUB', 'as' => 'customsClearance.exportProductEUB']);
    Route::post('customsClearance/exportProductFed',
        ['uses' => 'CustomsClearanceController@exportProductFed', 'as' => 'customsClearance.exportProductFed']);

    Route::get('customsClearance/bao3packageindex',
        ['uses' => 'CustomsClearanceController@bao3packageindex', 'as' => 'customsClearance.bao3packageindex']);
    Route::get('customsClearance/downloadToNanjing',
        ['uses' => 'CustomsClearanceController@downloadToNanjing', 'as' => 'customsClearance.downloadToNanjing']);
    Route::get('customsClearance/downloadOver',
        ['uses' => 'CustomsClearanceController@downloadOver', 'as' => 'customsClearance.downloadOver']);
    Route::post('customsClearance/updateNanjing',
        ['uses' => 'CustomsClearanceController@updateNanjing', 'as' => 'customsClearance.updateNanjing']);
    Route::post('customsClearance/updateOver',
        ['uses' => 'CustomsClearanceController@updateOver', 'as' => 'customsClearance.updateOver']);

    Route::get('customsClearance/bao3index',
        ['uses' => 'CustomsClearanceController@bao3index', 'as' => 'customsClearance.bao3index']);
    Route::post('customsClearance/updateNumber',
        ['uses' => 'CustomsClearanceController@updateNumber', 'as' => 'customsClearance.updateNumber']);
    Route::get('customsClearance/downloadUpdateProduct', [
        'uses' => 'CustomsClearanceController@downloadUpdateProduct',
        'as' => 'customsClearance.downloadUpdateProduct'
    ]);
    Route::get('customsClearance/downloadNumber',
        ['uses' => 'CustomsClearanceController@downloadNumber', 'as' => 'customsClearance.downloadNumber']);
    Route::get('customsClearance/downloadUploadProduct', [
        'uses' => 'CustomsClearanceController@downloadUploadProduct',
        'as' => 'customsClearance.downloadUploadProduct'
    ]);
    Route::post('customsClearance/uploadProduct',
        ['uses' => 'CustomsClearanceController@uploadProduct', 'as' => 'customsClearance.uploadProduct']);
    Route::post('customsClearance/updateProduct',
        ['uses' => 'CustomsClearanceController@updateProduct', 'as' => 'customsClearance.updateProduct']);
    Route::resource('customsClearance', 'CustomsClearanceController');

    //仓库
    Route::resource('warehouse', 'WarehouseController');

    //库存调整
    Route::post('stockAdjustment/checkResult/{id}',
        ['uses' => 'Stock\AdjustmentController@checkResult', 'as' => 'stockAdjustment.checkResult']);
    Route::get('stockAdjustment/adjustAdd',
        ['uses' => 'Stock\AdjustmentController@ajaxAdjustAdd', 'as' => 'stockAdjustment.adjustAdd']);
    Route::get('stockAdjustment/check/{id}',
        ['uses' => 'Stock\AdjustmentController@Check', 'as' => 'stockAdjustment.check']);
    Route::resource('stockAdjustment', 'Stock\AdjustmentController');

    //库位
    Route::get('position/ajaxCheckPosition',
        ['uses' => 'Warehouse\PositionController@ajaxCheckPosition', 'as' => 'position.ajaxCheckPosition']);
    Route::post('position/excelProcess',
        ['uses' => 'Warehouse\PositionController@excelProcess', 'as' => 'position.excelProcess']);
    Route::get('position/importByExcel',
        ['uses' => 'Warehouse\PositionController@importByExcel', 'as' => 'position.importByExcel']);
    Route::get('position/getExcel', ['uses' => 'Warehouse\PositionController@getExcel', 'as' => 'position.getExcel']);
    Route::get('position/getPosition',
        ['uses' => 'Warehouse\PositionController@ajaxGetPosition', 'as' => 'position.getPosition']);
    Route::resource('warehousePosition', 'Warehouse\PositionController');

    //库存
    Route::get('stock/getSinglePosition', ['uses' => 'StockController@getSinglePosition', 'as' => 'stock.getSinglePosition']);
    Route::get('stock/getSingleSku', ['uses' => 'StockController@getSingleSku', 'as' => 'stock.getSingleSku']);
    Route::get('stock/showStockInfo', ['uses' => 'StockController@showStockInfo', 'as' => 'stock.showStockInfo']);
    Route::get('stock/getExcel', ['uses' => 'StockController@getExcel', 'as' => 'stock.getExcel']);
    Route::post('stock/excelProcess', ['uses' => 'StockController@excelProcess', 'as' => 'stock.excelProcess']);
    Route::get('stock/importByExcel', ['uses' => 'StockController@importByExcel', 'as' => 'stock.importByExcel']);
    Route::get('stock/ajaxPosition', ['uses' => 'StockController@ajaxPosition', 'as' => 'stock.ajaxPosition']);
    Route::get('stock/ajaxSku', ['uses' => 'StockController@ajaxSku', 'as' => 'stock.ajaxSku']);
    Route::get('stock/createTaking', ['uses' => 'StockController@createTaking', 'as' => 'stock.createTaking']);
    Route::get('stock/allotSku', ['uses' => 'StockController@ajaxAllotSku', 'as' => 'stock.allotSku']);
    Route::get('stock/allotOutWarehouse',
        ['uses' => 'StockController@ajaxAllotOutWarehouse', 'as' => 'stock.allotOutWarehouse']);
    Route::get('stock/getByPosition', ['uses' => 'StockController@ajaxGetByPosition', 'as' => 'stock.getByPosition']);
    Route::get('stock/allotPosition', ['uses' => 'StockController@ajaxAllotPosition', 'as' => 'stock.allotPosition']);
    Route::get('stock/getMessage', ['uses' => 'StockController@ajaxGetMessage', 'as' => 'stock.getMessage']);
    Route::get('stock/allotPosition', ['uses' => 'StockController@ajaxAllotPosition', 'as' => 'stock.allotPosition']);


//采购条目
    Route::any('purchaseItem/cancelThisItem/{id}', 'Purchase\PurchaseItemController@cancelThisItem');
    Route::any('/purchaseItem/costExamineStatus/{id}/{costExamineStatus}',
        'Purchase\PurchaseItemController@costExamineStatus');
    Route::resource('purchaseItem', 'Purchase\PurchaseItemController');
//采购需求
    Route::any('/addPurchaseOrder', 'Purchase\RequireController@addPurchaseOrder');
    Route::resource('require', 'Purchase\RequireController');
//未结算订单
    Route::resource('closePurchaseOrder', 'Purchase\ClosePurchaseOrderController');

//采购单e
    Route::any('/purchaseOrder/updateItemWaitTime/{id}', 'Purchase\PurchaseOrderController@updateItemWaitTime');
    Route::any('/purchaseOrder/updateWaitTime/{id}', 'Purchase\PurchaseOrderController@updateWaitTime');
    Route::any('/purchaseOrder/createItem/{id}', 'Purchase\PurchaseOrderController@createItem');
    Route::any('/purchaseOrder/addItem/{id}', 'Purchase\PurchaseOrderController@addItem');
    Route::any('purchaseOrder/changeExamineStatus/{id}/{examinStatus}',
        'Purchase\PurchaseOrderController@changeExamineStatus');
    Route::any('purchaseOrder/examinePurchaseOrder', 'Purchase\PurchaseOrderController@examinePurchaseOrder');
    Route::any('purchaseOrder/excelOut/{id}', 'Purchase\PurchaseOrderController@excelOut');
    Route::any('purchaseOrder/purchaseOrdersOut', 'Purchase\PurchaseOrderController@purchaseOrdersOut');
    Route::any('purchaseOrder/excelOrderOut/{num}', 'Purchase\PurchaseOrderController@excelOrderOut');
    Route::any('/purchaseOrder/cancelOrder/{id}', 'Purchase\PurchaseOrderController@cancelOrder');
    Route::get('postAdd', ['uses' => 'Purchase\PurchaseOrderController@ajaxPostAdd', 'as' => 'postAdd']);
    Route::resource('purchaseOrder', 'Purchase\PurchaseOrderController');
//打印采购单
    Route::any('/checkWarehouse/address', 'Purchase\PrintPurchaseOrderController@warehouseAddress');
    Route::any('/checkWarehouse', 'Purchase\PrintPurchaseOrderController@checkWarehouse');
    Route::resource('printPurchaseOrder', 'Purchase\PrintPurchaseOrderController');
//采购列表
    Route::any('purchaseList/stockIn/{id}', 'Purchase\PurchaseListController@stockIn');
    Route::any('purchaseList/generateDarCode/{id}', 'Purchase\PurchaseListController@generateDarCode');
    Route::any('purchaseList/printBarCode/{id}', 'Purchase\PurchaseListController@printBarCode');
    Route::any('purchaseList/activeChange/{id}', 'Purchase\PurchaseListController@activeChange');
    Route::any('purchaseList/updateActive/{id}', 'Purchase\PurchaseListController@updateActive');
    Route::any('/changeItemWeight', 'Purchase\PurchaseListController@changeItemWeight');
    Route::any('/changePurchaseItemPostcoding', 'Purchase\PurchaseListController@changePurchaseItemPostcoding');
    Route::any('examinePurchaseItem',
        ['uses' => 'Purchase\PurchaseListController@examinePurchaseItem', 'as' => 'examinePurchaseItem']);
    Route::resource('purchaseList', 'Purchase\PurchaseListController');
//异常条目采购
    Route::resource('purchaseAbnormal', 'Purchase\PurchaseAbnormalController');
//异常单采购
    Route::any('purchaseOrderAbnormal/cancelOrder/{id}', 'Purchase\PurchaseOrderAbnormalController@cancelOrder');
    Route::resource('purchaseOrderAbnormal', 'Purchase\PurchaseOrderAbnormalController');
//采购入库
    Route::any('/purchaseStockIn/updateStorage', 'Purchase\PurchaseStockInController@updateStorage');
    Route::get('/manyStockIn', ['uses' => 'Purchase\PurchaseStockInController@manyStockIn', 'as' => 'manyStockIn']);
    Route::resource('purchaseStockIn', 'Purchase\PurchaseStockInController');
//采购条目
    Route::any('/purchaseItemList/postExcelReduction', 'Purchase\PurchaseItemListController@postExcelReduction');
    Route::any('/purchaseItemList/excelReductionUpdatePost',
        'Purchase\PurchaseItemListController@excelReductionUpdatePost');
    Route::any('/purchaseItemList/excelReductionUpdate', 'Purchase\PurchaseItemListController@excelReductionUpdate');
    Route::any('/purchaseItemList/purchaseItemPriceExcel',
        'Purchase\PurchaseItemListController@purchaseItemPriceExcel');
    Route::any('/purchaseItemList/purchaseItemPostExcel', 'Purchase\PurchaseItemListController@purchaseItemPostExcel');
    Route::any('/purchaseItemList/excelReduction', 'Purchase\PurchaseItemListController@excelReduction');
    Route::any('/purchaseItemList/reduction', 'Purchase\PurchaseItemListController@purchaseItemReduction');
    Route::any('/purchaseItemList/reductionUpdate', 'Purchase\PurchaseItemListController@reductionUpdate');
    Route::any('/purchaseItemList/itemReductionUpdate/{id}', 'Purchase\PurchaseItemListController@itemReductionUpdate');
    Route::resource('purchaseItemList', 'Purchase\PurchaseItemListController');
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
    Route::get('item.getImage', ['uses' => 'ItemController@getImage', 'as'=>'item.getImage']);
    Route::resource('item', 'ItemController');

    //渠道路由
    Route::resource('channel', 'ChannelController');

    //渠道账号路由
    Route::any('channelAccount/getAccountUser',
        ['uses' => 'Channel\AccountController@getAccountUser', 'as' => 'getAccountUser']);
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
    Route::get('allotment/pick/{id}', ['uses' => 'Stock\AllotmentController@allotmentpick', 'as' => 'allotment.pick']);
    Route::get('allotment/check/{id}',
        ['uses' => 'Stock\AllotmentController@allotmentCheck', 'as' => 'allotment.check']);
    Route::resource('stockAllotment', 'Stock\AllotmentController');

    //库存结转
    Route::post('stockCarryOver/showStockView',
        ['uses' => 'Stock\CarryOverController@showStockView', 'as' => 'stockCarryOver.showStockView']);
    Route::get('stockCarryOver/showStock',
        ['uses' => 'Stock\CarryOverController@showStock', 'as' => 'stockCarryOver.showStock']);
    Route::get('stockCarryOver/createCarryOver',
        ['uses' => 'Stock\CarryOverController@createCarryOver', 'as' => 'stockCarryOver.createCarryOver']);
    Route::post('stockCarryOver/createCarryOverResult',
        ['uses' => 'Stock\CarryOverController@createCarryOverResult', 'as' => 'stockCarryOver.createCarryOverResult']);
    Route::resource('stockCarryOver', 'Stock\CarryOverController');

    //库存盘点
    Route::get('StockTaking/takingAdjustmentShow/{id}',
        ['uses' => 'Stock\TakingController@takingAdjustmentShow', 'as' => 'StockTaking.takingAdjustmentShow']);
    Route::get('StockTaking/takingCreate',
        ['uses' => 'Stock\TakingController@ajaxtakingCreate', 'as' => 'stockTaking.takingCreate']);
    Route::get('StockTaking/takingCheck/{id}',
        ['uses' => 'Stock\TakingController@takingCheck', 'as' => 'stockTaking.takingCheck']);
    Route::post('StockTaking/takingCheckResult/{id}',
        ['uses' => 'Stock\TakingController@takingCheckResult', 'as' => 'stockTaking.takingCheckResult']);
    Route::resource('stockTaking', 'Stock\TakingController');

    //物流限制
    Route::resource('logisticsLimits', 'Logistics\LimitsController');

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
    Route::get('batchAddTrCode/{logistic_id}',
        ['uses' => 'Logistics\CodeController@batchAddTrCode', 'as' => 'batchAddTrCode']);
    Route::post('logisticsCodeFn', ['uses' => 'Logistics\CodeController@batchAddTrCodeFn', 'as' => 'logisticsCodeFn']);
    Route::get('scanAddTrCode/{logistic_id}',
        ['uses' => 'Logistics\CodeController@scanAddTrCode', 'as' => 'scanAddTrCode']);
    Route::post('scanAddTrCodeFn', ['uses' => 'Logistics\CodeController@scanAddTrCodeFn', 'as' => 'scanAddTrCodeFn']);
    Route::resource('logisticsRule', 'Logistics\RuleController');
    Route::get('bhw', ['uses' => 'Logistics\RuleController@bhw', 'as' => 'bhw']);

    //拣货单异常
    Route::get('errorList/ajaxProcess',
        ['uses' => 'Picklist\ErrorListController@ajaxProcess', 'as' => 'errorList.ajaxProcess']);
    Route::resource('errorList', 'Picklist\ErrorListController');

    //拣货路由
    Route::post('pickList/statisticsProcess', ['uses' => 'PickListController@statisticsProcess', 'as' => 'pickList.statisticsProcess']);
    Route::get('pickList/performanceStatistics', ['uses' => 'PickListController@performanceStatistics', 'as' => 'pickList.performanceStatistics']);
    Route::get('pickList/oldPrint', ['uses' => 'PickListController@oldPrint', 'as' => 'pickList.oldPrint']);
    Route::get('pickList/updatePrint', ['uses' => 'PickListController@updatePrint', 'as' => 'pickList.updatePrint']);

    Route::post('pickList/processBase', ['uses' => 'PickListController@processBase', 'as' => 'pickList.processBase']);
    Route::get('pickList/indexPrintPickList/{content}', ['uses' => 'PickListController@indexPrintPickList', 'as' => 'pickList.indexPrintPickList']);
    Route::post('pickList/inboxStore/{id}', ['uses' => 'PickListController@inboxStore', 'as' => 'pickList.inboxStore']);
    Route::post('pickList/createPickStore',
        ['uses' => 'PickListController@createPickStore', 'as' => 'pickList.createPickStore']);
    Route::get('pickList/createPick', ['uses' => 'PickListController@createPick', 'as' => 'pickList.createPick']);
    Route::get('pickList/inboxResult',
        ['uses' => 'PickListController@ajaxInboxResult', 'as' => 'pickList.inboxResult']);
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
    Route::get('choseShop', ['uses' => 'ProductController@choseShop', 'as' => 'choseShop']);
    Route::any('product/examineProduct',
        ['uses' => 'Product\EditProductController@examineProduct', 'as' => 'examineProduct']);
    Route::any('product/editImage',
        ['uses' => 'Product\EditProductController@productEditImage', 'as' => 'productEditImage']);
    Route::any('product/updateImage',
        ['uses' => 'Product\EditProductController@productUpdateImage', 'as' => 'productUpdateImage']);
    Route::resource('product', 'ProductController');
    Route::any('examineProduct/examineAll',
        ['uses' => 'Product\ExamineProductController@examineAll', 'as' => 'productExamineAll']);
    Route::resource('ExamineProduct', 'Product\ExamineProductController');

    //产品渠道
    Route::any('beChosed', ['uses' => 'Product\SelectProductController@beChosed', 'as' => 'beChosed']);
    Route::any('product/price', ['uses' => 'Product\EditProductController@price', 'as' => 'productPrice']);
    Route::resource('EditProduct', 'Product\EditProductController');
    Route::resource('SelectProduct', 'Product\SelectProductController');
    Route::resource('PublishProduct', 'Product\PublishProductController');
    Route::get('cancelExamineAmazonProduct',
        [
            'uses' => 'Product\Channel\AmazonController@cancelExamineAmazonProduct',
            'as' => 'cancelExamineAmazonProduct'
        ]);


    //订单管理路由
    Route::resource('order', 'OrderController');
    Route::resource('orderItem', 'Order\ItemController');
    Route::get('orderAdd', ['uses' => 'OrderController@ajaxOrderAdd', 'as' => 'orderAdd']);
    Route::resource('orderBlacklist', 'Order\BlacklistController');
    Route::any('blacklist/listAll', ['uses' => 'Order\BlacklistController@listAll', 'as' => 'listAll']);

    //包裹管理路由
    Route::get('package/ctrlZ', ['uses' => 'PackageController@ctrlZ', 'as' => 'package.ctrlZ']);
    Route::get('package/manualLogistics', ['uses' => 'PackageController@manualLogistics', 'as' => 'package.manualLogistics']);
    Route::get('package/manualShipping', ['uses' => 'PackageController@manualShipping', 'as' => 'package.manualShipping']);
    Route::get('package/setManualLogistics', ['uses' => 'PackageController@setManualLogistics', 'as' => 'package.setManualLogistics']);
    Route::get('package/ajaxQuantityProcess', ['uses' => 'PackageController@ajaxQuantityProcess', 'as' => 'package.ajaxQuantityProcess']);

    Route::get('package/allocateLogistics/{id}',
        ['uses' => 'PackageController@allocateLogistics', 'as' => 'package.allocateLogistics']);
    Route::post('package/storeAllocateLogistics/{id}',
        ['uses' => 'PackageController@storeAllocateLogistics', 'as' => 'package.storeAllocateLogistics']);
    Route::post('package/excelProcessFee/{type}',
        ['uses' => 'PackageController@excelProcessFee', 'as' => 'package.excelProcessFee']);
    Route::get('package/returnTrackno', ['uses' => 'PackageController@returnTrackno', 'as' => 'package.returnTrackno']);
    Route::post('package/excelProcess', ['uses' => 'PackageController@excelProcess', 'as' => 'package.excelProcess']);
    Route::get('package/returnFee', ['uses' => 'PackageController@returnFee', 'as' => 'package.returnFee']);
    Route::get('package/exportManualPackage/{str}',
        ['uses' => 'PackageController@exportManualPackage', 'as' => 'package.exportManualPackage']);
    Route::get('package/ajaxWeight', ['uses' => 'PackageController@ajaxWeight', 'as' => 'package.ajaxWeight']);
    Route::post('package/exportData', ['uses' => 'PackageController@exportData', 'as' => 'package.exportData']);
    Route::get('package/shippingStatistics',
        ['uses' => 'PackageController@shippingStatistics', 'as' => 'package.shippingStatistics']);
    Route::get('package/ajaxShippingExec',
        ['uses' => 'PackageController@ajaxShippingExec', 'as' => 'package.ajaxShippingExec']);
    Route::get('package/shipping', ['uses' => 'PackageController@shipping', 'as' => 'package.shipping']);
    Route::get('package/ajaxPackageSend',
        ['uses' => 'PackageController@ajaxPackageSend', 'as' => 'package.ajaxPackageSend']);
    Route::any('package/ajaxGetOrder', ['uses' => 'PackageController@ajaxGetOrder', 'as' => 'package.ajaxGetOrder']);
    Route::get('package/doPackage',
        ['uses' => 'PackageController@doPackage', 'as' => 'package.doPackage']);
    Route::get('package/assignLogistics',
        ['uses' => 'PackageController@assignLogistics', 'as' => 'package.assignLogistics']);
    Route::get('package/placeLogistics',
        ['uses' => 'PackageController@placeLogistics', 'as' => 'package.placeLogistics']);
    Route::get('package/flow',
        ['uses' => 'PackageController@flow', 'as' => 'package.flow']);
    Route::resource('package', 'PackageController');

    Route::get('account', ['uses' => 'OrderController@account', 'as' => 'account']);
    Route::get('getMsg', ['uses' => 'OrderController@getMsg', 'as' => 'getMsg']);
    Route::get('getChoiesOrder', ['uses' => 'OrderController@getChoiesOrder', 'as' => 'getChoiesOrder']);
    Route::get('getCode', ['uses' => 'OrderController@getCode', 'as' => 'getCode']);
    Route::get('getAliExpressOrder', ['uses' => 'OrderController@getAliExpressOrder', 'as' => 'getAliExpressOrder']);

    //用户路由
    Route::resource('user', 'UserController');


});