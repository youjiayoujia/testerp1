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
use App\Models\Product\SupplierModel;

class DataController extends Controller
{
    public function __construct(smProduct $model)
    {

    }

    public function index()
    {
        $smProducts = smProduct::limit(100)->orderBy('products_id', 'desc')->get();
        foreach ($smProducts as $smProduct) {
            $item = [
                'catalog_id' => 0,
                'product_id' => 0,
                'sku' => $smProduct->products_sku,
                'name' => $smProduct->products_title,
                'c_name' => $smProduct->products_name_cn,
                'weight' => $smProduct->products_weight,
                'warehouse_id' => $smProduct->product_warehouse_id,
                'warehouse_position' => $smProduct->products_location,
//                'alias_name' => $smProduct->products_declared_en,
//                'alias_cname' => $smProduct->products_declared_cn,
//                'supplier_id' => SupplierModel::where('old_id', $smProduct->products_suppliers_id)->get()->id,
//                'supplier_sku' => '',
//                'second_supplier_id' => function ($smProduct) {
//                    $suppliers = explode(',', $smProduct->products_suppliers_id);
//                    if (isset($suppliers[1])) {
//                        $secondSupplier = SupplierModel::where('old_id', $suppliers[1])->get();
//                        if ($secondSupplier) {
//                            return $secondSupplier->id;
//                        }
//                    }
//                    return '';
//                },
                'second_supplier_sku' => '',
                'supplier_info' => $smProduct->products_sku,
                'purchase_url' => $smProduct->productsPhotoStandard,
                'purchase_price' => $smProduct->products_value,
                'purchase_carriage' => '',
                'cost' => $smProduct->products_value,
                'product_size' => function ($smProduct) {
                    if ($smProduct->products_volume) {
                        $volumes = unserialize($smProduct->products_volume);
                        return $volumes['bp']['length'] . '*' . $volumes['bp']['width'] . '*' . $volumes['bp']['height'];
                    }
                    return '';
                },
                'package_size' => function ($smProduct) {
                    if ($smProduct->products_volume) {
                        $volumes = unserialize($smProduct->products_volume);
                        return $volumes['ap']['length'] . '*' . $volumes['ap']['width'] . '*' . $volumes['ap']['height'];
                    }
                    return '';
                },
                'carriage_limit' => '',
                'package_limit' => '',
                'status' => $smProduct->products_status_2,
                'is_sale' => $smProduct->productsIsActive,
                'remark' => $smProduct->products_remark,
            ];
            ItemModel::create($item);
        }
    }
}