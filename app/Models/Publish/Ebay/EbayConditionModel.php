<?php
/**ebay 物品状况模型
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-28
 * Time: 14:57
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
class EbayConditionModel extends BaseModel
{
    protected $table = 'ebay_condition';
    protected $fillable = [
        'condition_id',
        'condition_name',
        'category_id',
        'site',
        'is_variations',
        'is_condition',
        'is_upc',
        'is_ean',
        'is_isbn',
        'last_update_time',
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

}