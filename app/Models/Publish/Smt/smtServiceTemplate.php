<?php

namespace App\models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class smtServiceTemplate extends Model
{
    protected $table = "smt_service_template";
    protected $fillable = ['id','token_id','serviceID','serviceName','last_update_time'];
}
