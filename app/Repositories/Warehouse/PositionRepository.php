<?php
/**
 * 库位仓库类 
 * 规则定义与具体操作
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/18
 * Time:16:21
 */

namespace App\Repositories\Warehouse;

use App\Base\BaseRepository;
use App\Models\Warehouse\PositionModel as Position;

class PositionRepository extends BaseRepository
{
	// 查询
	protected $searchFields = ['name'];

	// 用于规则验证
    public $rules = [
        'create' => [
        	'name' => 'required|max:128|unique:warehouse_positions,name',
			'warehouses_id' => 'required',
			'size' => 'required'
			],
        'update' => [
        	'name' => 'required|max:128|unique:warehouse_positions,name,{id}',
			'warehouses_id' => 'required',
			'size' => 'required'
			]
    ];

	public function __construct(Position $position)
	{
		$this->model = $position;
	}
}