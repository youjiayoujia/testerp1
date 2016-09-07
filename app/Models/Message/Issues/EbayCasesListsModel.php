<?php
/**
 * Created by PhpStorm.
 * User: di
 * Date: 2016/8/13
 * Time: 15:31
 */
namespace App\Models\Message\Issues;
use App\Base\BaseModel;
use App\Models\OrderModel;
use App\Models\Order\ItemModel;
class EbayCasesListsModel extends BaseModel{
    protected $table = 'ebay_cases_lists';
    public $rules = [];
    public $searchFields =[];
    protected $guarded = [];

    public function account()
    {
        return $this->hasOne('App\Models\Channel\AccountModel', 'id', 'account_id');
    }
    public function orderItem(){
        return $this->hasOne('App\Models\Order\ItemModel','id','related_order_id');
    }

    public function getCaseContentAttribute(){
        $html = '';
        $note = unserialize(base64_decode($this->content));

        //dd($note);

        if(is_array($note)){
            if(isset($note['role'])){ //单条
                if($note['role'] == 'BUYER'){
                    $html .= '<div class="alert alert-warning col-md-10" role="alert">';
                    $html .= '<p>buyer:'.$this->buyer_id.'</p>';
                    $html .= '<p>seller:'.$this->seller_id.'</p>';
                    $html .= '<p>状态:'.$this->seller_id.'</p>';
                    $html .= '<p>activity:'.$this->seller_id.'</p>';
                    $html .= '<p>Date:'.$note['creationDate'].'</p>';
                    $html .= '<p>Date:'.$note['note'].'</p>';
                    $html .= '<div class="" style="display: none;"><strong>翻译结果: </strong><p class="content"></p></div>';
                    $html .= '<button style="float: right;" type="button" class="btn btn-success btn-translation" need-translation-content="">
                                    翻译
                                </button>';
                    $html .= '</div>';
                }else{
                    $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right">';
                    $html .= '<p>buyer:'.$this->buyer_id.'</p>';
                    $html .= '<p>seller:'.$this->seller_id.'</p>';
                    $html .= '<p>状态:'.$this->seller_id.'</p>';
                    $html .= '<p>activity:'.$this->seller_id.'</p>';
                    $html .= '<p>Date:'.$note['creationDate'].'</p>';
                    $html .= '<p>note:'.$note['note'].'</p>';
                    $html .= '</div>';
                }

            }else{ //多条
                foreach (array_reverse($note) as $item){
                    if($item['role'] == 'BUYER'){
                        $html .= '<div class="alert alert-warning col-md-10" role="alert">';

                        $html .= '<p>Date:'.$item['creationDate'].'</p>';
                        $html .= '<p>Date:'.$item['note'].'</p>';
                        $html .= '<div class="" style="display: none;"><strong>翻译结果: </strong><p class="content"></p></div>';
                        $html .= '<button style="float: right;" type="button" class="btn btn-success btn-translation" need-translation-content="">
                                    翻译
                                </button>';
                        $html .= '</div>';
                    }else{
                        $html .= '<div class="alert alert-success col-md-10" role="alert" style="float: right">';
                        $html .= '<p>activity:'.$this->seller_id.'</p>';
                        $html .= '<p>Date:'.$item['creationDate'].'</p>';
                        $html .= '<p>note:'.$item['note'].'</p>';
                        $html .= '</div>';
                    }
                }
            }




        }


        return $html;
    }

    public function getCaseOrderInfoAttribute()
    {
        if (!empty($this->transaction_id)) {
            $realted_order = ItemModel::where('transaction_id', $this->transaction_id)->first();
            if (!empty($realted_order)) {
                return $realted_order;
            }
        }
        return '';
    }
}


































