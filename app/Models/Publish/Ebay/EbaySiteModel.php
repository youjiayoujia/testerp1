<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-01
 * Time: 14:31
 */

namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
class EbaySiteModel extends BaseModel
{

    protected $table = 'ebay_site';

    protected $fillable = [
        'site',
        'site_id',
        'detail_version',
        'returns_accepted',
        'returns_with_in',
        'shipping_costpaid_by',
        'refund',
        'is_use'
    ];

    protected $searchFields = [];

 /*   protected $rules = [
        'create' => [
            'seller_code' => 'required',
            'user_id' => 'required',
        ],
        'update' => [
            'seller_code' => 'required',
            'user_id' => 'required',

        ]
    ];*/

    public function getSiteEnableAttribute()
    {
        return $this->is_use==1 ? '是' : '否';
    }


}