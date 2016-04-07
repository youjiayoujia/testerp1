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

use Excel;
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
use App\Models\PackageModel;

class TestController extends Controller
{
    public function test()
    {
        var_dump(request('id'));
    }
/**
     * 投诉excel导出
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    // public function test()
    // {
    //     $rows = [['id'=>'', 'name'=>'']];
    //     $name = '2.xls';
    //     Excel::create($name, function($excel) use ($rows){
    //         $nameSheet='投诉列表';
    //         $excel->sheet($nameSheet, function($sheet) use ($rows){
    //             $sheet->fromArray($rows);
    //         });
    //     })->download('xls');
        
    //     /*Excel::create('/public/cexcel/', function($excel) {
    //     $excel->sheet('productcomplaint', function($sheet) {
    //         $sheet->loadView('folder.view');
    //     });
    //     });*/
    // }
    /**
     * 投诉excel导入
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    // public function test()
    // {
    //      $filePath = '/storage/excel/123.xls';
    //         Excel::load($filePath, function($reader) {
    //         $data = $reader->all();
    //         var_dump($data->toArray());exit;
    //         });
    // }


//     public function test()
//     {
//         // $arr = PackageModel::all();
//         /******************************/
//         // $arr = PackageModel::has('order.channel_id','=',2)->get();

//         // var_dump($arr->toArray());
//         /*****************************/
//         $arr = PackageModel::with('order')->where(function($query){
//             var_dump($query->get()->toArray());
//             exit;
//         });
// exit;
//         var_dump($arr->toArray());
//         // foreach($arr as $v){
//         //     var_dump($v);
//         //     echo $v->order->id.$v->order->channel_id."<br>";
//         // }
//         // $query = ListItemModel::where(['picklist_id'=>'0','type'=>'SINGLE'])->get();
//         // $lists = $query->chunk(2);
//         // foreach($lists as $list){
//         //     foreach($list as $item){
//         //         echo $item->id.'--';
//         //         $item->update(['picklist_id'=>'1']);
//         //     }
//         // }
//         // exit;
//         // // var_dump($query);exit;
//         // if($query->count()) {
//         //     $query->chunk(2, function($picklistItems){
//         //         var_dump($picklistItems);
//         //         foreach($picklistItems as $picklistItem) {
//         //             echo "<hr>";
//         //             // var_dump($picklistItem);
//         //             echo $picklistItem->id;
//         //             echo "--".$picklistItem->picklist_id;
//         //             $picklistItem->update(['picklist_id'=>'1']);
//         //         }
//         //     });
//         //  }
//     }

    // public function test1($url)
    // {
    //     var_dump($url);
    // }
}