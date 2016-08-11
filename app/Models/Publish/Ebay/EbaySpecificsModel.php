<?php
/** ebay 细节模型
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-07-28
 * Time: 17:04
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
class EbaySpecificsModel extends BaseModel
{
    protected $table = 'ebay_specifics';
    protected $fillable = [
        'name',
        'category_id',
        'site',
        'value_type',
        'min_values',
        'max_values',
        'selection_mode',
        'variation_specifics',
        'specific_values',
        'last_update_time'
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

}