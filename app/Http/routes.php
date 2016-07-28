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
/**
 * 
 * Route::get('a/b', ['uses' => 'dddController@b', 'as' => 'a.b']);   路由规范
 * 注意a/b  b  a.b 这三部分的样式就OK了
 *
 */
Route::get('test1', 'TestController@test1');
Route::get('test2', 'TestController@test2');
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::group(['middleware' => 'auth'], function () {
    //Home
    Route::any('/', ['as' => 'dashboard.index', 'uses' => 'PackageController@flow']);
    //国家
    Route::resource('countries', 'CountriesController');
    //国家分类
    Route::resource('countriesSort', 'CountriesSortController');

    Route::resource('eventChild', 'EventChildController');
    //3宝package
    Route::resource('bao3Package', 'Bao3PackageController');
    //产品图片路由
    Route::any('productImage/imageLable', ['uses' => 'Product\ImageController@imageLable', 'as' => 'imageLable']);
    Route::any('productImage/createImage', ['uses' => 'Product\ImageController@createImage', 'as' => 'createImage']);
    Route::resource('productImage', 'Product\ImageController');
    //reported smissing  reportedMissingCreate
    Route::post('reportedMissingCreate', 'product\ReportedMissingController@store');
    Route::resource('reportedMissing', 'Product\ReportedMissingController');
    //包装限制
    Route::resource('wrapLimits', 'WrapLimitsController');
    Route::any('catalog/checkName', ['uses' => 'CatalogController@checkName', 'as' => 'checkName']);
    //汇率
    Route::resource('currency', 'CurrencyController');
    //关帐
    Route::resource('stockShut', 'Stock\ShutController');
    //hold库存
    Route::resource('stockHold', 'Stock\HoldController');
    //unhold库存
    Route::resource('stockUnhold', 'Stock\UnholdController');
    //入库
    Route::resource('stockIn', 'Stock\InController');
    //出库
    Route::resource('stockOut', 'Stock\OutController');
    //出入库
    Route::resource('stockInOut', 'Stock\InOutController');
    //供货商变更历史
    Route::resource('supplierChangeHistory', 'Product\SupplierChangeHistoryController');
    //供货商评级
    Route::resource('supplierLevel', 'Product\SupplierLevelController');
    //供货商
    Route::get('productSupplier/ajaxSupplier',
        ['uses' => 'Product\SupplierController@ajaxSupplier', 'as' => 'ajaxSupplier']);
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
    Route::get('stock/changePosition', ['uses' => 'StockController@changePosition', 'as' => 'stock.changePosition']);
    Route::any('itemAjaxWarehousePosition', ['uses' => 'StockController@ajaxWarehousePosition', 'as' => 'itemAjaxWarehousePosition']);
    Route::get('stock/getSinglePosition',
        ['uses' => 'StockController@getSinglePosition', 'as' => 'stock.getSinglePosition']);
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
    Route::get('stock/allotPosition', ['uses' => 'StockController@ajaxAllotPosition', 'as' => 'stock.allotPosition']);
    Route::get('stock/getMessage', ['uses' => 'StockController@ajaxGetMessage', 'as' => 'stock.getMessage']);
    Route::get('stock/ajaxGetByPosition',
        ['uses' => 'StockController@ajaxGetByPosition', 'as' => 'stock.ajaxGetByPosition']);
    Route::get('stock/ajaxGetOnlyPosition',
        ['uses' => 'StockController@ajaxGetOnlyPosition', 'as' => 'stock.ajaxGetOnlyPosition']);
    Route::resource('stock', 'StockController');
    //采购条目
    Route::any('purchaseItem/cancelThisItem/{id}', 'Purchase\PurchaseItemController@cancelThisItem');
    Route::any('purchaseItem/deletePurchaseItem', ['uses' => 'Purchase\PurchaseItemController@deletePurchaseItem', 'as' => 'deletePurchaseItem']);
    Route::any('/purchaseItem/costExamineStatus/{id}/{costExamineStatus}',
        'Purchase\PurchaseItemController@costExamineStatus');
    Route::resource('purchaseItem', 'Purchase\PurchaseItemController');
    Route::any('beExamine', ['uses' => 'Product\SupplierController@beExamine', 'as' => 'beExamine']);
    //采购需求
    Route::any('/addPurchaseOrder', 'Purchase\RequireController@addPurchaseOrder');
    Route::resource('require', 'Purchase\RequireController');
    //未结算订单
    Route::resource('closePurchaseOrder', 'Purchase\ClosePurchaseOrderController');
    //采购单
    Route::any('/purchaseOrder/addPost/{id}', 'Purchase\PurchaseOrderController@addPost');
    Route::any('PurchaseOrder/trackingNoSearch',
        ['uses' => 'Purchase\PurchaseOrderController@trackingNoSearch', 'as' => 'trackingNoSearch']);
    Route::any('purchaseOrder/changePrintStatus',
        ['uses' => 'Purchase\PurchaseOrderController@changePrintStatus', 'as' => 'changePrintStatus']);
    Route::any('purchaseOrder/payOrder/{id}',
        ['uses' => 'Purchase\PurchaseOrderController@payOrder', 'as' => 'payOrder']);
    Route::any('purchaseList/ajaxScan', ['uses' => 'Purchase\PurchaseListController@ajaxScan', 'as' => 'ajaxScan']);
    Route::any('purchaseOrder/recieve', ['uses' => 'Purchase\PurchaseOrderController@recieve', 'as' => 'recieve']);
    Route::any('purchaseOrder/printpo', ['uses' => 'Purchase\PurchaseOrderController@printpo', 'as' => 'printpo']);
    Route::any('purchaseOrder/ajaxInWarehouse',
        ['uses' => 'Purchase\PurchaseOrderController@ajaxInWarehouse', 'as' => 'ajaxInWarehouse']);
    Route::any('purchaseOrder/inWarehouse',
        ['uses' => 'Purchase\PurchaseOrderController@inWarehouse', 'as' => 'inWarehouse']);
    Route::any('purchaseOrder/ajaxRecieve',
        ['uses' => 'Purchase\PurchaseOrderController@ajaxRecieve', 'as' => 'ajaxRecieve']);
    Route::any('purchaseOrder/updateArriveNum',
        ['uses' => 'Purchase\PurchaseOrderController@updateArriveNum', 'as' => 'updateArriveNum']);
    Route::any('purchaseOrder/updateArriveLog',
        ['uses' => 'Purchase\PurchaseOrderController@updateArriveLog', 'as' => 'updateArriveLog']);
    Route::any('/purchaseOrder/updateItemWaitTime/{id}', 'Purchase\PurchaseOrderController@updateItemWaitTime');
    Route::any('/purchaseOrder/updateWaitTime/{id}', 'Purchase\PurchaseOrderController@updateWaitTime');
    Route::any('/purchaseOrder/createItem/{id}', 'Purchase\PurchaseOrderController@createItem');
    Route::any('/purchaseOrder/addItem/{id}', 'Purchase\PurchaseOrderController@addItem');
    Route::any('purchaseOrder/changeExamineStatus/{id}/{examinStatus}',
        'Purchase\PurchaseOrderController@changeExamineStatus');
    Route::any('purchaseOrder/examinePurchaseOrder', 'Purchase\PurchaseOrderController@examinePurchaseOrder');
    Route::any('purchaseOrder/excelOut/{id}', 'Purchase\PurchaseOrderController@excelOut');
    Route::any('purchaseOrder/write_off/{id}', 'Purchase\PurchaseOrderController@write_off');
    Route::any('purchaseOrder/purchaseOrdersOut', 'Purchase\PurchaseOrderController@purchaseOrdersOut');
    Route::any('purchaseOrder/excelOrderOut/{num}', 'Purchase\PurchaseOrderController@excelOrderOut');
    Route::any('/purchaseOrder/cancelOrder/{id}', 'Purchase\PurchaseOrderController@cancelOrder');
    Route::any('/purchaseOrder/printOrder/{id}', 'Purchase\PurchaseOrderController@printOrder');
    Route::any('postAdd', ['uses' => 'Purchase\PurchaseOrderController@ajaxPostAdd', 'as' => 'postAdd']);
    Route::resource('purchaseOrder', 'Purchase\PurchaseOrderController');
    //打印采购单
    Route::any('/checkWarehouse/address', 'Purchase\PrintPurchaseOrderController@warehouseAddress');
    Route::any('/checkWarehouse', 'Purchase\PrintPurchaseOrderController@checkWarehouse');
    Route::resource('printPurchaseOrder', 'Purchase\PrintPurchaseOrderController');
    //采购列表
    Route::any('purchaseItemArrival',
        ['uses' => 'Purchase\PurchaseListController@purchaseItemArrival', 'as' => 'purchaseItemArrival']);
    Route::any('selectPurchaseOrder',
        ['uses' => 'Purchase\PurchaseListController@selectPurchaseOrder', 'as' => 'selectPurchaseOrder']);
    Route::any('deletePostage', ['uses' => 'Purchase\PurchaseListController@deletePostage', 'as' => 'deletePostage']);
    Route::any('binding', ['uses' => 'Purchase\PurchaseListController@binding', 'as' => 'binding']);
    Route::any('purchaseList/stockIn/{id}', 'Purchase\PurchaseListController@stockIn');
    Route::any('purchaseList/generateDarCode/{id}', 'Purchase\PurchaseListController@generateDarCode');
    Route::any('purchaseList/printBarCode/{id}', 'Purchase\PurchaseListController@printBarCode');
    Route::any('purchaseList/activeChange/{id}', 'Purchase\PurchaseListController@activeChange');
    Route::any('purchaseList/updateActive/{id}', 'Purchase\PurchaseListController@updateActive');
    Route::any('/changeItemWeight', 'Purchase\PurchaseListController@changeItemWeight');
    Route::any('/changePurchaseItemPostcoding', 'Purchase\PurchaseListController@changePurchaseItemPostcoding');
    Route::any('/changePurchaseItemStorageQty', 'Purchase\PurchaseListController@changePurchaseItemStorageQty');
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

    //品类路由
    Route::resource('catalog', 'CatalogController');
    Route::get('catalog/exportCatalogRates/{str}',
        ['uses' => 'CatalogController@exportCatalogRates', 'as' => 'catalog.exportCatalogRates']);
    Route::get('catalog/editCatalogRates/{str}',
        ['uses' => 'CatalogController@editCatalogRates', 'as' => 'catalog.editCatalogRates']);
    Route::any('updateCatalogRates', ['uses' => 'CatalogController@updateCatalogRates', 'as' => 'updateCatalogRates']);

    //item路由
    Route::get('item.getModel', ['uses' => 'ItemController@getModel', 'as' => 'item.getModel']);
    Route::get('item/print', ['uses' => 'ItemController@printsku', 'as' => 'item.print']);
    Route::get('item.getImage', ['uses' => 'ItemController@getImage', 'as' => 'item.getImage']);
    Route::resource('item', 'ItemController');
    //渠道路由
    Route::resource('channel', 'ChannelController');
    //渠道账号路由
    Route::any('channelAccount/getAccountUser',
        ['uses' => 'Channel\AccountController@getAccountUser', 'as' => 'getAccountUser']);
    Route::post('channelAccount/updateApi/{id}',
        ['uses' => 'Channel\AccountController@updateApi', 'as' => 'channelAccount.updateApi']);
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
    
    //物流渠道路由
    Route::resource('logisticsChannelName', 'Logistics\ChannelNameController');
    //物流路由
    Route::get('logisticsCode/one/{id}',
        ['uses' => 'Logistics\CodeController@one', 'as' => 'logisticsCode.one']);
    Route::get('logistics/getLogistics',
        ['uses' => 'LogisticsController@getLogistics', 'as' => 'logistics.getLogistics']);
    Route::resource('logistics', 'LogisticsController');
    Route::resource('logisticsSupplier', 'Logistics\SupplierController');
    Route::resource('logisticsCode', 'Logistics\CodeController');
    Route::get('logisticsZone/getCountries',
        ['uses' => 'Logistics\ZoneController@getCountries', 'as' => 'logisticsZone.getCountries']);
    Route::get('logisticsZone/sectionAdd',
        ['uses' => 'Logistics\ZoneController@sectionAdd', 'as' => 'logisticsZone.sectionAdd']);
    Route::resource('logisticsZone', 'Logistics\ZoneController');
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
    Route::resource('logisticsCatalog', 'Logistics\CatalogController');
    Route::resource('logisticsEmailTemplate', 'Logistics\EmailTemplateController');
    Route::resource('logisticsTemplate', 'Logistics\TemplateController');
    Route::get('view/{id}', ['uses' => 'Logistics\TemplateController@view', 'as' => 'view']);
    Route::get('templateMsg/{id}', ['uses' => 'PackageController@templateMsg', 'as' => 'templateMsg']);
    //拣货单异常
    Route::get('errorList/ajaxProcess',
        ['uses' => 'Picklist\ErrorListController@ajaxProcess', 'as' => 'errorList.ajaxProcess']);
    Route::resource('errorList', 'Picklist\ErrorListController');
    //拣货路由

    Route::any('pickList/printException/',
        ['uses' => 'PickListController@printException', 'as' => 'pickList.printException']);
    Route::post('pickList/statisticsProcess',
        ['uses' => 'PickListController@statisticsProcess', 'as' => 'pickList.statisticsProcess']);
    Route::get('pickList/performanceStatistics',
        ['uses' => 'PickListController@performanceStatistics', 'as' => 'pickList.performanceStatistics']);
    Route::get('pickList/oldPrint', ['uses' => 'PickListController@oldPrint', 'as' => 'pickList.oldPrint']);
    Route::get('pickList/updatePrint', ['uses' => 'PickListController@updatePrint', 'as' => 'pickList.updatePrint']);
    Route::post('pickList/processBase', ['uses' => 'PickListController@processBase', 'as' => 'pickList.processBase']);
    Route::get('pickList/indexPrintPickList/{content}',
        ['uses' => 'PickListController@indexPrintPickList', 'as' => 'pickList.indexPrintPickList']);
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
    Route::any('productInfo', ['uses' => 'ProductController@productInfo', 'as' => 'productInfo']);
    Route::any('product/changePurchaseAdmin/{id}', ['uses' => 'ProductController@changePurchaseAdmin', 'as' => 'changePurchaseAdmin']);
    Route::any('productBatchEdit', ['uses' => 'ProductController@productBatchEdit', 'as' => 'productBatchEdit']);
    Route::any('productBatchUpdate', ['uses' => 'ProductController@productBatchUpdate', 'as' => 'productBatchUpdate']);
    Route::any('product/getCatalogProperty', 'ProductController@getCatalogProperty');
    Route::get('examine', ['uses' => 'ProductController@examine', 'as' => 'examine']);
    Route::get('productMultiEdit', ['uses' => 'ProductController@productMultiEdit', 'as' => 'productMultiEdit']);
    Route::any('productMultiUpdate', ['uses' => 'ProductController@productMultiUpdate', 'as' => 'productMultiUpdate']);
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
    Route::get('order/putNeedQueue',
        ['uses' => 'OrderController@putNeedQueue', 'as' => 'order.putNeedQueue']);
    Route::get('refund/{id}', ['uses' => 'OrderController@refund', 'as' => 'refund']);
    Route::any('batchEdit', ['uses' => 'ItemController@batchEdit', 'as' => 'batchEdit']);
    Route::any('batchUpdate', ['uses' => 'ItemController@batchUpdate', 'as' => 'batchUpdate']);
    Route::get('order/ajaxCountry', ['uses' => 'OrderController@ajaxCountry', 'as' => 'order.ajaxCountry']);
    Route::get('order/ajaxSku', ['uses' => 'OrderController@ajaxSku', 'as' => 'order.ajaxSku']);
    Route::resource('order', 'OrderController');
    Route::resource('orderItem', 'Order\ItemController');
    Route::get('orderAdd', ['uses' => 'OrderController@ajaxOrderAdd', 'as' => 'orderAdd']);
    Route::resource('orderBlacklist', 'Order\BlacklistController');
    Route::any('blacklist/listAll', ['uses' => 'Order\BlacklistController@listAll', 'as' => 'listAll']);
    Route::get('updateStatus', ['uses' => 'OrderController@updateStatus', 'as' => 'updateStatus']);
    Route::get('updatePrepared', ['uses' => 'OrderController@updatePrepared', 'as' => 'updatePrepared']);
    Route::get('updateNormal', ['uses' => 'OrderController@updateNormal', 'as' => 'updateNormal']);
    Route::get('withdraw/{id}', ['uses' => 'OrderController@withdraw', 'as' => 'withdraw']);
    Route::post('withdrawUpdate/{id}', ['uses' => 'OrderController@withdrawUpdate', 'as' => 'withdrawUpdate']);
    Route::any('refund/{id}', ['uses' => 'OrderController@refund', 'as' => 'refund']);
    Route::get('remark/{id}', ['uses' => 'OrderController@remark', 'as' => 'remark']);
    Route::post('remarkUpdate/{id}', ['uses' => 'OrderController@remarkUpdate', 'as' => 'remarkUpdate']);
    Route::post('refundUpdate/{id}', ['uses' => 'OrderController@refundUpdate', 'as' => 'refundUpdate']);
    Route::get('getBlacklist', ['uses' => 'Order\BlacklistController@getBlacklist', 'as' => 'getBlacklist']);
    Route::any('exportAll', ['uses' => 'Order\BlacklistController@exportAll', 'as' => 'exportAll']);
    Route::any('exportPart', ['uses' => 'Order\BlacklistController@exportPart', 'as' => 'exportPart']);
    Route::post('uploadBlacklist', ['uses' => 'Order\BlacklistController@uploadBlacklist', 'as' => 'uploadBlacklist']);
    Route::get('downloadUpdateBlacklist',
        ['uses' => 'Order\BlacklistController@downloadUpdateBlacklist', 'as' => 'downloadUpdateBlacklist']);
    //订单投诉
    Route::resource('orderComplaint', 'Order\OrderComplaintController');
    //包裹管理路由
    Route::get('package/ajaxUpdatePackageLogistics',
        ['uses' => 'PackageController@ajaxUpdatePackageLogistics', 'as' => 'package.ajaxUpdatePackageLogistics']);
    Route::get('package/ajaxReturnPackageId',
        ['uses' => 'PackageController@ajaxReturnPackageId', 'as' => 'package.ajaxReturnPackageId']);
    Route::get('package/multiPackage', ['uses' => 'PackageController@multiPackage', 'as' => 'package.multiPackage']);
    Route::get('package/ctrlZ', ['uses' => 'PackageController@ctrlZ', 'as' => 'package.ctrlZ']);
    Route::get('package/manualLogistics',
        ['uses' => 'PackageController@manualLogistics', 'as' => 'package.manualLogistics']);
    Route::get('package/manualShipping',
        ['uses' => 'PackageController@manualShipping', 'as' => 'package.manualShipping']);
    Route::get('package/setManualLogistics',
        ['uses' => 'PackageController@setManualLogistics', 'as' => 'package.setManualLogistics']);
    Route::get('package/ajaxQuantityProcess',
        ['uses' => 'PackageController@ajaxQuantityProcess', 'as' => 'package.ajaxQuantityProcess']);
    Route::get('package/downloadType', ['uses' => 'PackageController@downloadType', 'as' => 'package.downloadType']);
    Route::get('package/downloadFee', ['uses' => 'PackageController@downloadFee', 'as' => 'package.downloadFee']);
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
    //图片标签
    Route::resource('label', 'LabelController');
    Route::resource('paypal', 'PaypalController');
    //editOnlineProduct

    Route::post('wish/editOnlineProductStore', ['uses' => 'Publish\Wish\WishPublishController@editOnlineProductStore', 'as' => 'wish.editOnlineProductStore']);
    Route::get('wish/ajaxOperateOnlineProduct', ['uses' => 'Publish\Wish\WishPublishController@ajaxOperateOnlineProduct', 'as' => 'wish.ajaxOperateOnlineProduct']);
    Route::get('wish/ajaxEditOnlineProduct', ['uses' => 'Publish\Wish\WishPublishController@ajaxEditOnlineProduct', 'as' => 'wish.ajaxEditOnlineProduct']);
    Route::get('wish/indexOnlineProduct', ['uses' => 'Publish\Wish\WishPublishController@indexOnlineProduct', 'as' => 'wish.indexOnlineProduct']);
    Route::get('wish/editOnlineProduct', ['uses' => 'Publish\Wish\WishPublishController@editOnlineProduct', 'as' => 'wish.editOnlineProduct']);
    Route::resource('wish','Publish\Wish\WishPublishController');


    Route::resource('wishSellerCode','Publish\Wish\WishSellerCodeController');


    Route::get('ebayDetail/getEbayShipping', ['uses' => 'Publish\Ebay\EbayDetailController@getEbayShipping', 'as' => 'ebayDetail.getEbayShipping']);
    Route::get('ebayDetail/getEbayReturnPolicy', ['uses' => 'Publish\Ebay\EbayDetailController@getEbayReturnPolicy', 'as' => 'ebayDetail.getEbayReturnPolicy']);
    Route::get('ebayDetail/getEbaySite', ['uses' => 'Publish\Ebay\EbayDetailController@getEbaySite', 'as' => 'ebayDetail.getEbaySite']);
    Route::resource('ebayDetail','Publish\Ebay\EbayDetailController');



    Route::post('wish/editOnlineProductStore',
        ['uses' => 'Publish\Wish\WishPublishController@editOnlineProductStore', 'as' => 'wish.editOnlineProductStore']);
    Route::get('wish/ajaxOperateOnlineProduct', [
        'uses' => 'Publish\Wish\WishPublishController@ajaxOperateOnlineProduct',
        'as' => 'wish.ajaxOperateOnlineProduct'
    ]);
    Route::get('wish/ajaxEditOnlineProduct',
        ['uses' => 'Publish\Wish\WishPublishController@ajaxEditOnlineProduct', 'as' => 'wish.ajaxEditOnlineProduct']);
    Route::get('wish/indexOnlineProduct',
        ['uses' => 'Publish\Wish\WishPublishController@indexOnlineProduct', 'as' => 'wish.indexOnlineProduct']);
    Route::get('wish/editOnlineProduct',
        ['uses' => 'Publish\Wish\WishPublishController@editOnlineProduct', 'as' => 'wish.editOnlineProduct']);
    Route::resource('wish', 'Publish\Wish\WishPublishController');
    Route::resource('wishSellerCode', 'Publish\Wish\WishSellerCodeController');
    // Route::any('wishPublish',['uses'=>'Publish\Wish\WishPublishController@index','as'=>'wishPublish']);
    //开启工作流
    Route::any('message/startWorkflow',
        ['as' => 'message.startWorkflow', 'uses' => 'MessageController@startWorkflow']);
    //关闭工作流
    Route::any('message/{id}/endWorkflow',
        ['as' => 'message.endWorkflow', 'uses' => 'MessageController@endWorkflow']);
    //稍后处理
    Route::any('message/{id}/dontRequireReply',
        ['as' => 'message.dontRequireReply', 'uses' => 'MessageController@dontRequireReply']);
    //无需回复
    Route::any('message/{id}/notRequireReply',
        ['as' => 'message.notRequireReply', 'uses' => 'MessageController@notRequireReply']);
    //处理信息
    Route::any('message/process',
        ['as' => 'message.process', 'uses' => 'MessageController@process']);
    //邮件内容
    Route::any('message/{id}/content',
        ['as' => 'message.content', 'uses' => 'MessageController@content']);
    //邮件信息
    Route::resource('message', 'MessageController');
    //邮件转发控制器
    Route::any('message/{id}/foremail',
        ['as' => 'message.foremail', 'uses' => 'MessageController@foremail']);
    Route::get('forwardemail/edit/{id}', 'Message\ForemailController@edit');
    //转交他人
    Route::any('message/{id}/assignToOther',
        ['as' => 'message.assignToOther', 'uses' => 'MessageController@assignToOther']);
    Route::resource('message', 'MessageController');
    //设置关联订单
    Route::any('message/{id}/setRelatedOrders',
        ['as' => 'message.setRelatedOrders', 'uses' => 'MessageController@setRelatedOrders']);
    //取消关联订单
    Route::any('message/{id}/cancelRelatedOrder/{relatedOrderId}',
        ['as' => 'message.cancelRelatedOrder', 'uses' => 'MessageController@cancelRelatedOrder']);
    //无需关联订单
    Route::any('message/{id}/notRelatedOrder',
        ['as' => 'message.notRelatedOrder', 'uses' => 'MessageController@notRelatedOrder']);
    Route::resource('message', 'MessageController');
    //信息模版类型路由
    Route::any('messageTemplateType/ajaxGetChildren',
        ['as' => 'messageTemplateType.ajaxGetChildren', 'uses' => 'Message\Template\TypeController@ajaxGetChildren']);
    Route::any('messageTemplateType/ajaxGetTemplates',
        ['as' => 'messageTemplateType.ajaxGetTemplates', 'uses' => 'Message\Template\TypeController@ajaxGetTemplates']);
    Route::resource('messageTemplateType', 'Message\Template\TypeController');
    //信息模版路由
    Route::any('messageTemplate/ajaxGetTemplate',
        ['as' => 'messageTemplate.ajaxGetTemplate', 'uses' => 'Message\TemplateController@ajaxGetTemplate']);
    //回复信息
    Route::any('message/{id}/reply',
        ['as' => 'message.reply', 'uses' => 'MessageController@reply']);
    //信息模版路由
    Route::any('messageTemplate/ajaxGetTemplate',
        ['as' => 'messageTemplate.ajaxGetTemplate', 'uses' => 'Message\TemplateController@ajaxGetTemplate']);
    Route::resource('messageTemplate', 'Message\TemplateController');
    //新增单个无需回复
    Route::any('message/{id}/notRequireReply_1',
        ['as' => 'message.notRequireReply_1', 'uses' => 'MessageController@notRequireReply_1']);
    //转发邮件
    Route::resource('message_log', 'Message\Messages_logController');
    //回复队列路由
    Route::resource('messageReply', 'Message\ReplyController');


    //用户路由
    Route::get('productUser/ajaxUser', ['uses' => 'UserController@ajaxUser', 'as' => 'ajaxUser']);
    Route::get('productUser/ajaxSupplierUser', ['uses' => 'ProductController@ajaxSupplierUser', 'as' => 'ajaxSupplierUser']);
    Route::any('user/role',['uses' => 'UserController@per', 'as' => 'role']);
    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    //图片标签
    Route::resource('label', 'LabelController');
    Route::resource('paypal', 'PaypalController');

    //日志
    Route::resource('logCommand', 'Log\CommandController');
    Route::resource('logQueue', 'Log\QueueController');

    //队列
    Route::resource('jobFailed', 'Job\FailedController');
    //标记发货规则设置
    Route::resource('orderMarkLogic', 'Order\OrderMarkLogicController');
});


//getEbayInfo
Route::any('testReturnTrack', ['uses' => 'TestController@testReturnTrack']);
Route::any('getEbayInfo', ['uses' => 'TestController@getEbayInfo']);

Route::any('testtest', ['uses' => 'TestController@test', 'as' => 'test1']);
Route::any('test', ['uses' => 'TestController@index']);
Route::any('aliexpressOrdersList', ['uses' => 'TestController@aliexpressOrdersList']);
Route::any('lazadaOrdersList', ['uses' => 'TestController@lazadaOrdersList']);
Route::any('cdiscountOrdersList', ['uses' => 'TestController@cdiscountOrdersList']);
Route::any('getwishproduct', ['uses' => 'TestController@getWishProduct']);