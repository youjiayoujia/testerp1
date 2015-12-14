<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:11
 */

namespace App\Models;


use App\Base\BaseModel;

class LogisticsModel extends BaseModel
{
    protected $table = 'logistics';

    protected $fillable = ['name'];

}