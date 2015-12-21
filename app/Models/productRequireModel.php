<?php

namespace App\Models;

use App\Base\BaseModel;

class productRequireModel extends BaseModel
{
	protected $table = 'product_require';

	protected $fillable = ['img1', 'img2', 'img3', 'img4', 'img5', 'img6', 'name', 'province', 'city', 'similar_sku', 'competition_url', 'remark', 'expected_date', 'needer_id', 'needer_shop_id', 'created_by', 'status', 'user_id', 'handle_time'];
}