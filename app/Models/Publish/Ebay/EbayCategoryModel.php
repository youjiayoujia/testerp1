<?php
/** ebay站点分类
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-28
 * Time: 10:53
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
class EbayCategoryModel extends BaseModel
{
    protected $table = 'ebay_category';
    protected $fillable = [
        'category_id',
        'best_offer',
        'auto_pay',
        'category_level',
        'category_name',
        'category_parent_id',
        'leaf_category',
        'site',
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

}