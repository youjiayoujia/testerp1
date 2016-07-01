<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;
use App\Models\Sellmore\AmaLogisticsModel as smAmaLogistics;
use App\Models\Sellmore\WishLogisticsModel as smWishLogistics;
use App\Models\Sellmore\DhgateLogisticsModel as smDhgateLogistics;
use App\Models\Sellmore\LazadaLogisticsModel as smLazadaLogistics;
use App\Models\Sellmore\AliExpressLogisticsModel as smAliExpressLogistics;
use App\Models\Sellmore\ShipmentModel as smShipment;
use App\Models\Logistics\ChannelNameModel;


class ChannelLogistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:logistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Channel Logistics';

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
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'AliExpress'])->first()->id;
        $dhgates = smAliExpressLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                $originNum++;
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        LogisticsModel::find($logistics->shipmentID)->channelName()->sync([$id => ['name' => $dhgate->logistics_key]]);
                    }
                }
            }
            $dhgates = smAliExpressLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smAliExpressLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Cdiscount'])->first()->id;
        $originNum = 0;
        $smShipments = smShipment::skip($start)->take($len)->get();
        while ($smShipments->count()) {
            $start += $len;
            foreach ($smShipments as $smShipment) {
                $originNum++;
                $model = LogisticsModel::find($smShipment->shipmentID);
                if ($model) {
                    if ($smShipment->shipmentCdiscountCodeID) {
                        $model->channelName()->attach([$id => ['name' => $smShipment->shipmentCdiscountCodeID]]);
                    }
                } else {
                    var_dump($smShipment->shipmentID);
                }
            }
            $smShipments = smShipment::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smShipment-cdiscount]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'Lazada'])->first()->id;
        $dhgates = smLazadaLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                $originNum++;
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        LogisticsModel::find($logistics->shipmentID)->channelName()->attach([$id => ['name' => $dhgate->logistics_name]]);
                    }
                }
            }
            $dhgates = smLazadaLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smLazadaLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'Dhgate'])->first()->id;
        $dhgates = smDhgateLogistics::skip($start)->take($len)->get();
        while ($dhgates->count()) {
            $start += $len;
            foreach ($dhgates as $dhgate) {
                $originNum++;
                if ($dhgate->logisticses) {
                    foreach ($dhgate->logisticses as $logistics) {
                        LogisticsModel::find($logistics->shipmentID)->channelName()->attach([$id => ['name' => $dhgate->logistics_name]]);
                    }
                }
            }
            $dhgates = smDhgateLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smDhgateLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $originNum = 0;
        $id = ChannelModel::where(['name' => 'Wish'])->first()->id;
        $wishes = smWishLogistics::skip($start)->take($len)->get();
        while ($wishes->count()) {
            $start += $len;
            foreach ($wishes as $wish) {
                $originNum++;
                if ($wish->logisticses) {
                    foreach ($wish->logisticses as $logistics) {
                        LogisticsModel::find($logistics->shipmentID)->channelName()->attach([$id => ['name' => $wish->logistics_name]]);
                    }
                }
            }
            $wishes = smWishLogistics::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smWishLogistics]: Origin:'.$originNum);

        $len = 100;
        $start = 0;
        $id = ChannelModel::where(['name' => 'Amazon'])->first()->id;
        $originNum = 0;
        $smShipments = smShipment::skip($start)->take($len)->get();
        while ($smShipments->count()) {
            $start += $len;
            foreach ($smShipments as $smShipment) {
                $originNum++;
                $model = LogisticsModel::find($smShipment->shipmentID);
                if ($model) {
                    $model->channelName()->attach([$id => ['name' => $smShipment->shipmentAMZCode]]);
                } else {
                    var_dump($smShipment->shipmentID);
                }
            }
            $smShipments = smShipment::skip($start)->take($len)->get();
        }
        $this->info('Transfer [smShipment-amazon]: Origin:'.$originNum);
    }
}
