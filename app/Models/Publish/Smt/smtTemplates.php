<?php

namespace App\Models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class smtTemplates extends Model
{
    protected $table = "smt_template";
    
    protected $fillable = ['id','plat','token_id','name','pic_path','content'];
}
