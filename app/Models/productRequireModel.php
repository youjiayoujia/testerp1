<?php

namespace App\Models;

use App\Base\BaseModel;

class productRequireModel extends BaseModel
{
	protected $table = 'product_require';

	protected $fillable = ['name', 'address', 'similar_sku', 'competition_url', 'remark'];
}