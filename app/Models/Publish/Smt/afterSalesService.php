<?php

namespace App\models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class afterSalesService extends Model
{
    protected $table = "after_sales_service";
    protected $fillable = ['plat','token_id','name','content'];
}