<?php

/**
 * 范例: 供应商库
 *
 * @author MC<178069409@qq.com>
 */

namespace App\Repositories;

use Config;
use App\Base\BaseRepository;
use App\Models\productRequireModel as productRequire;

class productRequireRepository extends BaseRepository
{
		/*
	*
	* @func 用于查询
	*
	*/
	protected $searchFields = ['name'];

	/**
	*
	* 规则验证
	*
	*/
    public $rules = [
        'create' => ['name' => 'required|unique:product_require,name'],
        'update' => []
    ];

	function __construct(productRequire $productRequire)
	{
		$this->model = $productRequire;
	}

	public function store($data)
	{
		if(!array_key_exists('id', $data)) {
			$productRequire = $this->model->create($data)->id;
		} else {
			$productRequire = productRequire::where('id', '=', $data['id'])->update($data);
		}

		return $productRequire;
	}

}