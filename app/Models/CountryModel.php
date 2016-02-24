<?php
/**
 *国家模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/8
 * Time: 上午9:44
 */

namespace App\Models;

use App\Base\BaseModel;

class CountryModel extends BaseModel
{
    protected $table = 'countries';

    protected $fillable = ['name', 'abbreviation'];

}