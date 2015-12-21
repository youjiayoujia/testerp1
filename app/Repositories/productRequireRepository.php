<?php

/**
 * 选款需求库 
 * 定义规则与具体操作
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/18
 * Time:17:26
 */

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\productRequireModel as productRequire;

class productRequireRepository extends BaseRepository
{
	// 用于查询
	protected $searchFields = ['name'];

	// 规则验证
    public $rules = [
        'create' => [	
        		'name' => 'required|max:255|unique:product_require,name',
            	'competition_url' => 'active_url',
            	'needer_id' => 'required',
            	'needer_shop_id' => 'required'
        ],
        'update' => [	
        		'name' => 'required|max:255|unique:product_require,name, {id}',
            	'competition_url' => 'active_url',
            	'needer_id' => 'required',
            	'needer_shop_id' => 'required',
        ]
    ];

	function __construct(productRequire $productRequire)
	{
		$this->model = $productRequire;
	}

    /**
     * 数据保存
     * 
     * @param $data array
     * @return Object
     *
     */
	public function store($data)
	{
		$buf = $this->model->create($data); 
		return $buf;
	}

    /**
     * 数据更新
     *
     * @param $id integer 记录id
     * @param $data array 数据
     * @retrun Object 
     *
     */
    public function update($id, $data)
    {
        $buf = $this->get($id);
        $buf->update($data);

        return $buf;
    }
}