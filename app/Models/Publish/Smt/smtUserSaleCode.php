<?php

namespace App\Models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class smtUserSaleCode extends Model
{
    //
    protected $table = "smt_user_sale_code";
    
    public $fillable = ['user_id','sale_code'];
    
    public function belongsToUser(){
        return $this->belongsTo('App\Models\UserModel','user_id');
    }
}
