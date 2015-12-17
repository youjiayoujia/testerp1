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
        'create' => [	
        		//'name' => 'required|max:255|unique:product_require,name',
            	'competition_url' => 'active_url',
            	'needer_id' => 'required',
            	'needer_shop_id' => 'required'
        ],
        'update' => [	
        		'name' => 'required|max:255|unique:product_require,name,{id}',
            	'competition_url' => 'active_url',
            	'needer_id' => 'required',
            	'needer_shop_id' => 'required',
        ]
    ];

	function __construct(productRequire $productRequire)
	{
		$this->model = $productRequire;
	}

	public function store($data)
	{
		if(!array_key_exists('id', $data)) {
            $data['address'] = $data['province'].' '.$data['city'];
			$buf = $this->model->create($data); 
			$productRequire = $buf->id;
		} else {
			$buf = $this->model->find($data['id']);
			$productRequire = $buf->update($data);
		}

		return $productRequire;
	}

}