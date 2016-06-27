<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/6/24
 * Time: 上午11:05
 */
namespace App\Http\Controllers;

use Tool;
use App\Models\Sellmore\ProductModel as smProduct;
use App\Models\ItemModel;
use App\Models\Sellmore\SupplierModel as smSupplier;
use App\Models\Product\SupplierModel;
use App\Models\Channel\AccountModel;
use App\Models\Sellmore\AmazonModel as smAmazon;
use App\Models\Sellmore\WishModel as smWish;
use App\Models\Sellmore\SmtModel as smSmt;
use App\Models\Sellmore\LazadaModel as smLazada;


class DataController extends Controller
{
    public function transfer_lazada()
    {
        $len = 100;
        $start = 0;
        $smLazadas = smLazada::skip($start)->take($len)->get();
        while ($smLazadas->count()) {
            $start += $len;
            foreach ($smLazadas as $smLazada) {
                $lazada = [
                    'channel_id' => '2',
                    'country_id' => '0',
                    'sync_cycle' => '0',
                    'sync_days' => 30,
                    'sync_pages' => 100,
                    'lazada_access_key' => $smLazada->Key,
                    'lazada_user_id' => $smLazada->lazada_user_id,
                    'lazada_site' => $smLazada->site,
                    'lazada_currency_type' => $smLazada->currency_type,
                    'lazada_currency_type_cn' => $smLazada->currency_type_cn,
                    'lazada_api_host' => $smLazada->api_host,
                ];
                var_dump($lazada);
                exit;
                AccountModel::create($lazada);
            }
            $smLazadas = smLazada::skip($start)->take($len)->get();
        }
    }

    public function transfer_smt()
    {
        $len = 100;
        $start = 0;
        $smSmts = smSmt::skip($start)->take($len)->get();
        while ($smSmts->count()) {
            $start += $len;
            foreach ($smSmts as $smSmt) {
                $smt = [
                    'channel_id' => '2',
                    'country_id' => '0',
                    'sync_cycle' => '0',
                    'sync_days' => 30,
                    'sync_pages' => 100,
                    'aliexpress_member_id' => $smSmt->member_id,
                    'aliexpress_appkey' => $smSmt->appkey,
                    'aliexpress_appsecret' => $smSmt->appsecret,
                    'aliexpress_returnurl' => $smSmt->returnurl,
                    'aliexpress_refresh_token' => $smSmt->refresh_token,
                    'aliexpress_access_token' => $smSmt->access_token,
                    'aliexpress_access_token_date' => $smSmt->access_token_date,
                    'operator_id' => $smSmt->customerservice_id,
                    'customer_service_id' => $smSmt->customerservice_id,
                ];
                AccountModel::create($smt);
            }
            $smSmts = smSmt::skip($start)->take($len)->get();
        }
    }

    public function transfer_wish()
    {
        $len = 100;
        $start = 0;
        $smWishes = smWish::skip($start)->take($len)->get();
        while ($smWishes->count()) {
            $start += $len;
            foreach ($smWishes as $smWish) {
                $wish = [
                    'channel_id' => '4',
                    'country_id' => '0',
                    'account' => $smWish->account_name,
                    'sync_cycle' => '0',
                    'sync_days' => 30,
                    'sync_pages' => 100,
                    'wish_publish_code' => $smWish->publish_code,
                    'wish_client_id' => $smWish->client_id,
                    'wish_client_secret' => $smWish->client_secret,
                    'wish_redirect_uri' => $smWish->redirect_uri,
                    'wish_refresh_token' => $smWish->refresh_token,
                    'wish_access_token' => $smWish->access_token,
                    'wish_expiry_time' => $smWish->expiry_time,
                    'wish_proxy_address' => $smWish->proxy_address ? $smWish->proxy_address : '',
                    'wish_sku_resolve' => $smWish->sku_type,
                    'is_available' => $smWish->status,
                ];
                AccountModel::create($wish);
            }
            $smWishes = smWish::skip($start)->take($len)->get();
        }
    }

    public function __construct(smProduct $model)
    {
        set_time_limit(0);
    }

    public function index()
    {
        $smProducts = smProduct::limit(100)->orderBy('products_id', 'desc')->get();
        foreach ($smProducts as $smProduct) {
            $volumes = ['bp' => '', 'ap' => ''];
            if ($smProduct->products_volume) {
                $volumes = unserialize($smProduct->products_volume);
            }
            $supplierId = SupplierModel::where('old_id', $smProduct->products_suppliers_id)->get()->id;
            $secondSupplierId = 0;
            $suppliers = explode(',', $smProduct->products_suppliers_id);
            if (isset($suppliers[1])) {
                $secondSupplier = SupplierModel::where('old_id', $suppliers[1])->get();
                $secondSupplierId = $secondSupplier->id;
            }
            $item = [
                'catalog_id' => 0,
                'product_id' => 0,
                'sku' => $smProduct->products_sku,
                'name' => $smProduct->products_title,
                'c_name' => $smProduct->products_name_cn,
                'weight' => $smProduct->products_weight,
                'warehouse_id' => $smProduct->product_warehouse_id,
                'warehouse_position' => $smProduct->products_location,
                'alias_name' => $smProduct->products_declared_en,
                'alias_cname' => $smProduct->products_declared_cn,
                'supplier_id' => $supplierId,
                'supplier_sku' => '',
                'second_supplier_id' => $secondSupplierId,
                'second_supplier_sku' => '',
                'supplier_info' => $smProduct->products_sku,
                'purchase_url' => $smProduct->productsPhotoStandard,
                'purchase_price' => $smProduct->products_value,
                'purchase_carriage' => '',
                'cost' => $smProduct->products_value,
                'product_size' => $volumes['bp'],
                'package_size' => $volumes['ap'],
                'carriage_limit' => '',
                'package_limit' => '',
                'status' => $smProduct->products_status_2,
                'is_sale' => $smProduct->productsIsActive,
                'remark' => $smProduct->products_remark,
            ];
            ItemModel::create($item);
        }
    }

    public function transfer_supplier()
    {
        $len = 100;
        $start = 0;
        $smSuppliers = smSupplier::skip($start)->take($len)->get();
        while ($smSuppliers->count()) {
            $start += $len;
            foreach ($smSuppliers as $smSupplier) {
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
        $smAmazons = smAmazon::skip($start)->take($len)->get();
        while ($smAmazons->count()) {
            $start += $len;
            foreach ($smAmazons as $smAmazon) {
                $amazon = [
                    'channel_id' => '3',
                    'country_id' => '0',
                    'account' => $smAmazon->seller_account,
                    'alias' => $smAmazon->seller_account,
                    'order_prefix' => $smAmazon->seller_account,
                    'sync_cycle' => '0',
                    'sync_days' => 30,
                    'sync_pages' => 100,
                    'amazon_api_url' => $smAmazon->place_site,
                    'amazon_marketplace_id' => $smAmazon->place_id,
                    'amazon_seller_id' => $smAmazon->merchant_id,
                    'amazon_accesskey_id' => $smAmazon->access_key,
                    'amazon_accesskey_secret' => $smAmazon->secret_key,
                    'is_available' => $smAmazon->status,
                ];
                AccountModel::create($amazon);
            }
            $smAmazons = smAmazon::skip($start)->take($len)->get();
        }
    }
}