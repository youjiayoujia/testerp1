<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sellmore\ShipmentCategoryModel as smShipmentCategory;
use App\Models\Sellmore\ShipmentModel as smShipment;
use App\Models\Logistics\CatalogModel;
use App\Models\Logistics\SupplierModel as originSupplier;
use App\Models\LogisticsModel;
use App\Models\Sellmore\ShipmentSupplierModel as smShipmentSupplier;

class TransferLogistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:logistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $smShipmentCategorys = smShipmentCategory::skip($start)->take($len)->get();
        while ($smShipmentCategorys->count()) {
            $start += $len;
            foreach ($smShipmentCategorys as $smShipmentCategory) {
                $shipmentCategory = [
                    'id' => $smShipmentCategory->shipmentCatID,
                    'name' => $smShipmentCategory->shipmentCatName
                ];
                CatalogModel::create($shipmentCategory);
            }

            $smShipmentCategorys = smShipmentCategory::skip($start)->take($len)->get();
        }

        $len = 100;
        $start = 0;
        $smShipments = smShipment::skip($start)->take($len)->get();
        while ($smShipments->count()) {
            $start += $len;
            foreach ($smShipments as $smShipment) {
                $shipment = [
                    'id' => $smShipment->shipmentID,
                    'code' => $smShipment->shipmentTitle,
                    'name' => $smShipment->shipmentDescription,
                    'warehouse_id' => $smShipment->shipment_warehouse_id == '1025' ? '2' : '1',
                    'logistics_catalog_id' => $smShipment->shipmentCategoryID,
                    'is_enable' => '1',
                ];
                LogisticsModel::create($shipment);
            }

            $smShipments = smShipment::skip($start)->take($len)->get();
        }

        $len = 100;
        $start = 0;
        $smCds = smShipmentSupplier::skip($start)->take($len)->get();
        while ($smCds->count()) {
            $start += $len;
            foreach ($smCds as $smCd) {
                $cd = [
                    'id' => $smCd->suppliers_id,
                    'name' => $smCd->suppliers_name,
                    'client_manager' => $smCd->suppliers_services ? $smCd->suppliers_services : '',
                    'manager_tel' => $smCd->suppliers_services_phoneorqq,
                    'technician' => $smCd->suppliers_driver,
                    'technician_tel' => $smCd->suppliers_driver_phone,
                    'remark' => $smCd->suppliers_remark ? $smCd->suppliers_remark : '',
                    'bank' => $smCd->suppliers_bank,
                    'card_number' => $smCd->suppliers_card_number,
                ];
                originSupplier::create($cd);
            }
            $smCds = smShipmentSupplier::skip($start)->take($len)->get();
        }
    }
}
