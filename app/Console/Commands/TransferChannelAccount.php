<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChannelModel;
use App\Models\Channel\AccountModel;
use App\Models\Sellmore\AmazonModel as smAmazon;
use App\Models\Sellmore\WishModel as smWish;
use App\Models\Sellmore\SmtModel as smSmt;
use App\Models\Sellmore\LazadaModel as smLazada;
use App\Models\Sellmore\CdModel as smCd;
use App\Models\Sellmore\EbayModel as smEbay;

class TransferChannelAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:channelAccount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ChannelAccount';

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
        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Amazon'])->first()->id;
        $smAmazons = smAmazon::where(['method' => 'listOrders'])->skip($start)->take($len)->get();
        while ($smAmazons->count()) {
            $start += $len;
            foreach ($smAmazons as $smAmazon) {
                $url = parse_url($smAmazon->place_site);
                $amazon = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'account' => $smAmazon->seller_account,
                    'alias' => $smAmazon->seller_account,
                    'order_prefix' => $smAmazon->seller_account,
                    'sync_cycle' => '0',
                    'sync_days' => 30,
                    'sync_pages' => 100,
                    'amazon_api_url' => ($url['scheme']."://".$url['host']),
                    'amazon_marketplace_id' => $smAmazon->place_id,
                    'amazon_seller_id' => $smAmazon->merchant_id,
                    'amazon_accesskey_id' => $smAmazon->access_key,
                    'amazon_accesskey_secret' => $smAmazon->secret_key,
                    'is_available' => $smAmazon->status,
                ];
                AccountModel::create($amazon);
            }
            $smAmazons = smAmazon::where(['method' => 'listOrders'])->skip($start)->take($len)->get();
        }

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Wish'])->first()->id;
        $smWishes = smWish::skip($start)->take($len)->get();
        while ($smWishes->count()) {
            $start += $len;
            foreach ($smWishes as $smWish) {
                $wish = [
                    'channel_id' => $id,
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

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'AliExpress'])->first()->id;
        $smSmts = smSmt::skip($start)->take($len)->get();
        while ($smSmts->count()) {
            $start += $len;
            foreach ($smSmts as $smSmt) {
                $smt = [
                    'channel_id' => $id,
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

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Lazada'])->first()->id;
        $smLazadas = smLazada::skip($start)->take($len)->get();
        while ($smLazadas->count()) {
            $start += $len;
            foreach ($smLazadas as $smLazada) {
                $lazada = [
                    'channel_id' => $id,
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

                AccountModel::create($lazada);
            }
            $smLazadas = smLazada::skip($start)->take($len)->get();
        }

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Cdiscount'])->first()->id;
        $smCds = smCd::skip($start)->take($len)->get();
        while ($smCds->count()) {
            $start += $len;
            foreach ($smCds as $smCd) {
                $cd = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'sync_cycle' => '0',
                    'sync_days' => 30,
                    'sync_pages' => 100,
                    'cd_currency_type' => $smCd->currency_type,
                    'cd_currency_type_cn' => $smCd->currency_type_cn,
                    'cd_account' => $smCd->account,
                    'cd_token_id' => $smCd->token_id,
                    'cd_pw' => $smCd->pw,
                    'cd_sales_account' => $smCd->sales_account,
                    'cd_expires_in' => $smCd->expires_in
                ];
                AccountModel::create($cd);
            }
            $smCds = smCd::skip($start)->take($len)->get();
        }

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Ebay'])->first()->id;
        $smEbays = smEbay::skip($start)->take($len)->get();
        while ($smEbays->count()) {
            $start += $len;
            foreach ($smEbays as $smEbay) {
                $ebay = [
                    'channel_id' => $id,
                    'country_id' => '0',
                    'sync_cycle' => '0',
                    'sync_days' => 30,
                    'sync_pages' => 100,
                    'ebay_developer_account' => $smEbay->developer->developer_account,
                    'ebay_developer_devid' => $smEbay->developer->devid,
                    'ebay_developer_appid' => $smEbay->developer->appid,
                    'ebay_developer_certid' => $smEbay->developer->certid,
                    'ebay_token' => $smEbay->user_token,
                    'ebay_eub_developer' => $smEbay->eub_developer_id ? $smEbay->eub_developer_id : '',
                    'customer_service_id' => $smEbay->sf_order,
                ];
                AccountModel::create($ebay);
            }
            $smEbays = smEbay::skip($start)->take($len)->get();
        }
    }
}