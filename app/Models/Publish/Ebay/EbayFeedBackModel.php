<?php
/** ebay 评价模型
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-27
 * Time: 14:46
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
class EbayFeedBackModel extends BaseModel
{
    protected $table = 'ebay_feedback';
    protected $fillable = [
        'feedback_id',
        'channel_account_id',
        'commenting_user',
        'commenting_user_score',
        'comment_text',
        'comment_type',
        'ebay_item_id',
        'transaction_id',
        'comment_time',
    ];

    protected $searchFields = [];

       protected $rules = [
           'create' => [
           ],
           'update' => [
           ]
       ];

}