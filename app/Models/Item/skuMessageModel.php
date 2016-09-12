<?php

namespace App\Models\Item;

use App\Base\BaseModel;

class SkuMessageModel extends BaseModel
{
    protected $table = 'sku_messages';

	protected $guarded = [];

    //查询
    public $searchFields = ['id'=>'id'];

    public function questionUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'question_user');
    }

    public function answerUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'answer_user');
    }

}
