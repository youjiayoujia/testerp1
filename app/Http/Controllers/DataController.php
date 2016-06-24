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
use App\Models\Sellmore\SupplierModel as smSupplier;
use App\Models\Product\SupplierModel;
use App\Models\Channel\AccountModel;


class DataController extends Controller
{
    public function __construct(smProduct $model)
    {
        set_time_limit(0);
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

    public function transfer_supplier()
    {
        $len = 100;
        $start = 0;
        $smSuppliers = smSupplier::skip($start)->take($len)->get();
        while($smSuppliers->count()) {
            $start += $len;
            foreach($smSuppliers as $smSupplier) {
                $supplier = [
                    'old_id' => $smSupplier->suppliers_id,
                    'name' => $smSupplier->suppliers_name,
                    'contact_name' => $smSupplier->suppliers_name,
                    'address' => $smSupplier->suppliers_address,
                    'company' => $smSupplier->suppliers_company,
                    'url' => $smSupplier->suppliers_website, 
                    'official_url' => $smSupplier->suppliers_website, 
                    'telephone' => $smSupplier->suppliers_phone,
                    'purchase_time' => $smSupplier->supplierArrivalMinDays,
                    'bank_account' => $smSupplier->suppliers_bank,
                    'bank_code' => $smSupplier->suppliers_card_number,
                    'examine_status' => $smSupplier->suppliers_status,
                    'email' => $smSupplier->supplier_email ? $smSupplier->supplier_email : '',
                    'created_at' => $smSupplier->create_time,
                    'updated_at' => $smSupplier->modify_time,
                ];
                SupplierModel::create($supplier);
            }
            $smSuppliers = smSupplier::skip($start)->take($len)->get();
        }
    }

    public function transfer_amazon()
    {
        $len = 100;
        $start = 0;
        $smSuppliers = smSupplier::skip($start)->take($len)->get();
        while($smSuppliers->count()) {
            $start += $len;
            foreach($smSuppliers as $smSupplier) {
                $supplier = [
                    'old_id' => $smSupplier->suppliers_id,
                    'name' => $smSupplier->suppliers_name,
                    'contact_name' => $smSupplier->suppliers_name,
                    'address' => $smSupplier->suppliers_address,
                    'company' => $smSupplier->suppliers_company,
                    'url' => $smSupplier->suppliers_website, 
                    'official_url' => $smSupplier->suppliers_website, 
                    'telephone' => $smSupplier->suppliers_phone,
                    'purchase_time' => $smSupplier->supplierArrivalMinDays,
                    'bank_account' => $smSupplier->suppliers_bank,
                    'bank_code' => $smSupplier->suppliers_card_number,
                    'examine_status' => $smSupplier->suppliers_status,
                    'email' => $smSupplier->supplier_email ? $smSupplier->supplier_email : '',
                    'created_at' => $smSupplier->create_time,
                    'updated_at' => $smSupplier->modify_time,
                ];
                SupplierModel::create($supplier);
            }
            $smSuppliers = smSupplier::skip($start)->take($len)->get();
        }
    }
}