<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sellmore\ProductModel as smProduct;
use App\Models\WarehouseModel;
use App\Models\Product\SupplierModel;
use App\Models\Warehouse\PositionModel;
use App\Models\SpuModel;
use App\Models\ProductModel;
use App\Models\ItemModel;

class TransferProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (WarehouseModel::count() < 1) {
            $this->error('先导入仓库信息,深圳仓ID=1,义乌仓ID=2');
        }
        if (SupplierModel::count() < 1) {
            $this->error('先导入供货商信息');
        }
        $len = 1000;
        $start = 0;
        $createdNum[0] = 0;
        $updatedNum[0] = 0;
        $createdNum[1] = 0;
        $updatedNum[1] = 0;
        $originNum = 0;
        $createdNum[2] = 0;
        $updatedNum[2] = 0;
        $smProducts = smProduct::skip($start)->take($len)->get();
        while ($smProducts->count()) {
            $start += $len;
            foreach ($smProducts as $smProduct) {
                $originNum++;
                $spu = SpuModel::where(['spu' => $smProduct->products_sku])->first();

                if($spu) {
                    $updatedNum[0]++;
                    $spu->update(['spu' => $smProduct->products_sku]);
                } else {
                    $createdNum[0]++;
                    $spu = SpuModel::create(['spu' => $smProduct->products_sku]);
                }
                $arr = [];
                if ($smProduct->products_with_battery) {
                    $arr[] = 1;
                }
                if ($smProduct->products_with_adapter) {
                    $arr[] = 4;
                }
                if ($smProduct->products_with_fluid) {
                    $arr[] = 5;
                }
                if ($smProduct->products_with_powder) {
                    $arr[] = 2;
                }
                $buf = [
                    'model' => $smProduct->products_sku,
                    'parts' => $smProduct->products_parts_info ? $smProduct->products_parts_info : '',
                    'declared_cn' => $smProduct->products_declared_cn ? $smProduct->products_declared_cn : '',
                    'declared_en' => $smProduct->products_declared_en ? $smProduct->products_declared_en : '',
                    'declared_value' => $smProduct->products_declared_value ? $smProduct->products_declared_value : '',
                    'package_limit' => count($arr) ? implode(',', $arr) : '',
                    'catalog_id' => $smProduct->products_sort ? $smProduct->products_sort : '',
                    'name' => $smProduct->products_name_en ? $smProduct->products_name_en : '',
                    'c_name' => $smProduct->products_name_cn ? $smProduct->products_name_cn : '',
                    'supplier_id' => $smProduct->products_suppliers_id ? $smProduct->products_suppliers_id : '',
                    'warehouse_id' => $smProduct->product_warehouse_id == 1000 ? 1 : 2,
                    'hs_code' => $smProduct->product_hscode ? $smProduct->product_hscode : '',
                    'spu_id' => $spu->id,
                ];
                $tmp_product = ProductModel::where(['model' => $smProduct->products_sku])->first();
                if($tmp_product) {
                    $updatedNum[1]++;
                    $tmp_product->update($buf);
                } else {
                    $createdNum[1]++;
                    $tmp_product = ProductModel::create($buf);
                }
                unset($buf);

                //体积
                $volumes = ['product_size' => '', 'package_size' => ''];
                if ($smProduct->products_volume) {
                    $volumes = unserialize($smProduct->products_volume);
                    $volumes['product_size'] = isset($volumes['bp']) ? $volumes['bp']['length'] . '*' . $volumes['bp']['width'] . '*' . $volumes['bp']['height'] : '';
                    $volumes['package_size'] = isset($volumes['ap']) ? $volumes['ap']['length'] . '*' . $volumes['ap']['width'] . '*' . $volumes['ap']['height'] : '';
                }
                //供货商
                $supplier = SupplierModel::find($smProduct->products_suppliers_id);
                $supplierId = $supplier ? $supplier->id : 0;
                $secondSupplierId = 0;
                if ($smProduct->products_suppliers_ids) {
                    $supplierIds = explode(',', $smProduct->products_suppliers_ids);
                    if (isset($supplierIds[0])) {
                        if ($supplierIds[0] != $smProduct->products_suppliers_id) {
                            $secondSupplier = SupplierModel::find($supplierIds[0]);
                            $secondSupplierId = $secondSupplier ? $secondSupplier->id : 0;
                        }
                    }
                }
                //仓库
                $warehouseId = $smProduct->product_warehouse_id == 1000 ? 1 : 2;
                //库位
                if ($smProduct->products_location) {
                    $position = PositionModel::Where('name', $smProduct->products_location)->first();
                    if (!$position) {
                        PositionModel::create([
                            'name' => $smProduct->products_location,
                            'warehouse_id' => $warehouseId
                        ]);
                    }
                }
                $data = [
                    'catalog_id' => $smProduct->products_sort ? $smProduct->products_sort : '',
                    'sku' => $smProduct->products_sku,
                    'name' => $smProduct->products_title,
                    'c_name' => $smProduct->products_name_cn,
                    'weight' => $smProduct->products_weight,
                    'warehouse_id' => $warehouseId,
                    'warehouse_position' => $smProduct->products_location,
                    'supplier_id' => $supplierId,
                    'second_supplier_id' => $secondSupplierId,
                    'purchase_url' => $smProduct->productsPhotoStandard,
                    'purchase_price' => $smProduct->products_value,
                    'purchase_carriage' => '',
                    'cost' => $smProduct->products_value,
                    'product_size' => $volumes['product_size'],
                    'package_size' => $volumes['package_size'],
                    'carriage_limit' => '',
                    'package_limit' => '',
                    'status' => $smProduct->products_status_2,
                    'is_available' => $smProduct->productsIsActive,
                    'remark' => $smProduct->products_warring_string,
                    'product_id' => $tmp_product->id,
                ];
                $exist = ItemModel::where(['sku' => $smProduct->products_sku])->first();
                if($exist) {
                    $updatedNum[2]++;
                    $exist->update($data);
                } else {
                    $createdNum[2]++;
                    $data['id'] = $smProduct->products_id;
                    ItemModel::create($data);
                }
                unset($data);
            }
            $smProducts = smProduct::orderBy('products_id', 'desc')->skip($start)->take($len)->get();
        }
        $this->info('Transfer [Spu]: Origin:'.$originNum.' => Created:'.$createdNum[0].' Updated:'.$updatedNum[0]);
        $this->info('Transfer [Product]: Origin:'.$originNum.' => Created:'.$createdNum[1].' Updated:'.$updatedNum[1]);
        $this->info('Transfer [Item]: Origin:'.$originNum.' => Created:'.$createdNum[2].' Updated:'.$updatedNum[2]);
    }
}
