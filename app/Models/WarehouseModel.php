<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/3
 * Time: 下午3:56
 */

namespace App\Models;

use App\Base\BaseModel;

class WarehouseModel extends BrandModel
{
    protected $table = 'warehouses';

    protected $fillable = ['name'];
}