<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/6/24
 * Time: 上午11:05
 */
namespace App\Http\Controllers;

use App\Models\Sellmore\ProductModel as smProduct;
use App\Models\ItemModel;

class DataController extends Controller
{
    public function __construct(smProduct $model)
    {

    }

    public function index()
    {
        $smProducts = smProduct::limit(100)->get();
        foreach ($smProducts as $smProduct) {
            $item = [
                'catalog_id' => 0,
                'product_id' => 0,
                'sku' => $smProduct->products_sku,
                'name' => $smProduct->products_title,
                'c_name' => $smProduct->products_name_cn,
                'weight' => $smProduct->weightWithPacket,
//                'inventory' => $smProduct->products_sku,
                'warehouse_id' => $smProduct->product_warehouse_id,
                'warehouse_position' => $smProduct->products_location,
                'alias_name' => $smProduct->products_sku,
                'alias_cname' => $smProduct->products_sku,
                'supplier_id' => $smProduct->products_sku,
                'supplier_sku' => $smProduct->products_sku,
                'second_supplier_id' => $smProduct->products_sku,
                'second_supplier_sku' => $smProduct->products_sku,
                'supplier_info' => $smProduct->products_sku,
                'purchase_url' => $smProduct->products_sku,
                'purchase_price' => $smProduct->products_sku,
                'purchase_carriage' => $smProduct->products_sku,
                'cost' => $smProduct->products_sku,
                'product_size' => $smProduct->products_sku,
                'package_size' => $smProduct->products_sku,
                'carriage_limit' => $smProduct->products_sku,
                'package_limit' => $smProduct->products_sku,
                'status' => $smProduct->products_sku,
                'is_sale' => $smProduct->products_sku,
                'remark' => $smProduct->products_sku,
            ];
        }
    }
}