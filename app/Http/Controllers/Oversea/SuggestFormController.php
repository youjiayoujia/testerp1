<?php
/**
 * 脟镁碌脌驴脴脰脝脝梅
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\SuggestFormModel;;
use App\Models\Oversea\ChannelSaleModel;

class SuggestFormController extends Controller
{
    public function __construct(SuggestFormModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('suggestForm.index');
        $this->mainTitle = '建议采购数';
        $this->viewPath = 'oversea.suggestForm.';
    }

    public function createForms()
    {
        $channelSales = ChannelSaleModel::all()->groupBy('account_id');
        $this->model->truncate();
        foreach($channelSales as $channelSale) {
            foreach($channelSale->groupBy('item_id') as $singleItems) {
                $sevenSales = 0;
                $fourteenSales = 0;
                foreach($singleItems as $singleItem) {
                    if(strtotime($singleItem->create_time) > strtotime('-14 days')) {
                        $fourteenSales += $singleItem->quantity;
                        if(strtotime($singleItem->create_time) > strtotime('-7 days')) {
                            $sevenSales += $singleItem->quantity;
                        }
                    }
                }
                $single = $singleItems->first();
                $this->model->create(['item_id' => $single->item_id,
                                      'channel_sku' => $single->channel_sku,
                                      'sales_in_seven' => $sevenSales,
                                      'sales_in_fourteen' => $fourteenSales,
                                      'account_id' => $single->account_id]);
            }
        }

        return redirect($this->mainIndex);
    }
}