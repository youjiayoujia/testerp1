<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class ExportModel extends BaseModel
{
    protected $table = 'export_packages';

    protected $fillable = [
        'name'
    ];

    public function items()
    {
    	return $this->hasMany('App\Models\Package\ExportItemModel', 'parent_id', 'id');
    }

    public function inFields($name)
    {
    	$fields = $this->items;
    	foreach($fields as $field) {
    		if($name == $field->name) {
    			return $field->level;
    		}
    	}
    	
    	return false;
    }
}