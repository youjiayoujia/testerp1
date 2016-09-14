<?php

namespace App\Http\Controllers\Publish\Smt;

use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtPriceTaskMain;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;
use App\Models\Channel\AccountModel;
use App\Http\Requests\Request;


class SmtPriceTaskController extends Controller
{
    public function __construct(){
        $this->mainTitle = 'SMT调价任务';
        $this->mainIndex = route('smtPriceTask.index');
        $this->viewPath = 'publish.smt.';        
        $this->model = new smtPriceTaskMain();
        $this->channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
    }
    
    public function index(){      
       $accountInfo = AccountModel::where('channel_id',$this->channel_id)->get();
       $logisticsInfo = LogisticsModel::all();      
       
       $shipmentArr = array();
       foreach($logisticsInfo as $logistics){
           $shipmentArr[$logistics->id] = $logistics->code;
       }
       
       $accountInfoArr = array();
       foreach($accountInfo as $account){
           $accountInfoArr[$account->id] = $account->account;
       }       
       
       $response = [
           'metas' => $this->metas(__FUNCTION__),
           'data' => $this->autoList($this->model),
           'shipmentArr' => $shipmentArr,
           'accountInfoArr' => $accountInfoArr,           
       ];
       return view($this->viewPath . 'price_task_list', $response);       
    }
    
    public function batchDelete(){
        $ids = request()->input('Ids');
        $IDArr = explode(',', $ids);
        $msg = '';
        foreach($IDArr as $id){
            $status = $this->model->where('id',$id)->first()->status;
            if($status != 1){
                $msg .= "{$id}的记录已经执行，不允许删除<br/>";
                continue;
            }
            $result = $this->model->where('id',$id)->delete();
            if($result){
                $msg .= "{$id}的记录已经删除<br/>";
            }else{
                $msg .= "{$id}的记录删除失败<br/>";
            }
        }
        
        return array('info' => $msg,true);
    }
}
