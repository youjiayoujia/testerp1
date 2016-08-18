<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtUserSaleCode extends BaseModel
{
    //
    protected $table = "smt_user_sale_code";
    
    public $fillable = ['user_id','sale_code'];
    
    public function User(){
        return $this->belongsTo('App\Models\UserModel','user_id');
    }
}
