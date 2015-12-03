<?php

namespace App\Models;

use App\Base\BaseModel;

class FashionModel extends BaseModel
{
	protected $table = 'fashion_sel';

	protected $fillable = ['name', 'address', 'similar_sku', 'competition_url', 'remark'];
}