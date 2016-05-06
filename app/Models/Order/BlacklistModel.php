<?php
/**
 * 黑名单模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/5/5
 * Time: 下午7:44
 */

namespace App\Models\Order;

use App\Base\BaseModel;

class BlacklistModel extends BaseModel
{
    protected $table = 'order_blacklists';

    public $searchFields = ['name', 'email', 'zipcode'];

    protected $fillable = [
        'name',
        'email',
        'zipcode',
        'whitelist'
    ];

    public $rules = [
        'create' => [
            'name' => 'required',
            'email' => 'required',
            'zipcode' => 'required',
            'whitelist' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'email' => 'required',
            'zipcode' => 'required',
            'whitelist' => 'required',
        ],
    ];

}