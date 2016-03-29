<?php
/**
 * 库存控制器
 * 处理库存相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/30
 * Time: 14:19pm
 */

namespace App\Http\Controllers;

use Session;
use App\Models\StockModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\TakingModel;
use App\Models\Stock\TakingAdjustmentModel;
use App\Models\Stock\TakingFormModel;
use App\Models\AModel;
use App\Models\BModel;
use App\Models\CModel;
use App\Models\UserModel;
use App\Models\Pick\ListItemModel;

class TestController extends Controller
{
    public function test()
    {
        $query = ListItemModel::where(['picklist_id'=>'0','type'=>'SINGLE'])->get();
        $lists = $query->chunk(2);
        foreach($lists as $list){
            foreach($list as $item){
                echo $item->id.'--';
                $item->update(['picklist_id'=>'1']);
            }
        }
        exit;
        // var_dump($query);exit;
        if($query->count()) {
            $query->chunk(2, function($picklistItems){
                var_dump($picklistItems);
                foreach($picklistItems as $picklistItem) {
                    echo "<hr>";
                    // var_dump($picklistItem);
                    echo $picklistItem->id;
                    echo "--".$picklistItem->picklist_id;
                    $picklistItem->update(['picklist_id'=>'1']);
                }
            });
         }
    }

    public function test1($url)
    {
        var_dump($url);
    }
}