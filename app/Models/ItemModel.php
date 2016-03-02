<?php
namespace App\Models;

use App\Models\Product\SupplierModel;
use App\Base\BaseModel;
use App\Models\Warehouse\PositionModel;
use Exception;

class ItemModel extends BaseModel
{
    protected $table = 'items';

    protected $stock;

    public $searchFields = ['sku'];

    public $rules = [
        'update' => []
    ];

	protected $fillable = [
        'product_id','sku','weight','inventory','name','c_name','alias_name','alias_cname','catalog_id','supplier_id','supplier_sku','second_supplier_id','supplier_info','purchase_url'
        ,'purchase_price','purchase_carriage','product_size','package_size','carriage_limit','package_limit','status','remark'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id');
    }

    public function updateItem($data)
    {
        $data['carriage_limit'] = empty($data['carriage_limit_arr'])?'':implode(',', $data['carriage_limit_arr']);
        $data['package_limit'] = empty($data['package_limit_arr'])?'':implode(',', $data['package_limit_arr']);
        
        $this->update($data);
    }

    public function stocks()
    {
        return $this->hasMany('App\Models\StockModel', 'item_id');
    }

    /**
     * 获取库存对象 
     * @param $warehousePositionId 库位id
     * 
     * @return 库存对象
     *
     */
    public function getStock($warehousePosistionId)
    {
        $stock = $this->stocks()->where('warehouse_position_id', $warehousePosistionId)->first();
        if (!$stock) {
            $warehouse = PositionModel::where(['id'=>$warehousePosistionId])->first()->warehouse_id;
            $len = StockModel::where(['item_id'=>$this->id, 'warehouse_id'=>$warehouse])->count();
            if($len>=2) {
                throw new Exception('该sku对应的库位已经是2，且并没找到库位');
            }
            $stock = $this->stocks()->create(['warehouse_position_id'=>$warehousePosistionId, 'warehouse_id'=>$warehouse]);
        }

        return $stock;
    }

    /**
     * in api 
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     * $amount 金额
     * $type 类型
     * $relation_id 类型id
     * $remark 备注
     *
     * @return
     */
    public function in($warehousePosistionId, $quantity, $amount, $type, $relation_id, $remark='')
    {
        $stock = $this->getStock($warehousePosistionId);
        return $stock->in($quantity, $amount, $type, $relation_id, $remark);
    }

    /**
     * hold api 
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     *
     * @return
     */
    public function hold($warehousePosistionId, $quantity)
    {
        $stock = $this->getStock($warehousePosistionId);
        return $stock->hold($quantity);
    }

    /**
     * unhold api 
     * @param
     * $warehousePositionId 库位id
     * $quantity 数量
     * 
     * @return
     */
    public function unhold($warehousePosistionId, $quantity)
    {
        $stock = $this->getStock($warehousePosistionId);
        return $stock->unhold($quantity);
    }

    /**
     * out api 
     *
     * @param 
     * $warehousePoistionId 库位id
     * $quantity 数量
     * $type 类型
     * $relation_id 类型id
     * $remark 备注
     *
     * @return
     */
    public function out($warehousePosistionId, $quantity, $type, $relation_id, $remark='')
    {
        $stock = $this->getStock($warehousePosistionId);
        return $stock->out($quantity, $type, $relation_id, $remark);
    }
}
