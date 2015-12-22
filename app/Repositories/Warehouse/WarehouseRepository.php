<?php
/**
 * 仓库操作类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/18
 * Time:15:30
 *
 */
namespace App\Repositories\Warehouse;

use App\Base\BaseRepository;
use App\Models\Warehouse\WarehouseModel as Warehouse;

class WarehouseRepository extends BaseRepository
{
	// 用于查询
	protected $searchFields = ['name'];

	// 规则验证
    public $rules = [
        'create' => [
        	'name' => 'required|max:128|unique:warehouses,name',
			'type' => 'required',
			'volumn' => 'required|digits_between:1,10'
		],
        'update' => [
        	'name' => 'required|max:128|unique:warehouses,name,{id}',
			'type' => 'required',
			'volumn' => 'required|digits_between:1,10'
		]
    ];
    
	public function __construct(Warehouse $warehouse)
	{
		$this->model = $warehouse;
	}
}