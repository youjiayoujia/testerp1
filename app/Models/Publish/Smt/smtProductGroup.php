<?php

namespace App\Models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class smtProductGroup extends Model
{
    protected $table ="smt_product_group";
    
    protected $fillable = ['token_id','group_id','group_name','parent_id','last_update_time'];
}
