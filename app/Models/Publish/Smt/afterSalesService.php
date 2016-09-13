<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class afterSalesService extends BaseModel
{
    protected $table = "after_sales_service";
    protected $fillable = ['plat','token_id','name','content'];
    
    public $searchFields = ['name'=>'模版名称'];
    public function account(){
        return $this->belongsTo('App\Models\Channel\AccountModel', 'token_id');
    }
}
