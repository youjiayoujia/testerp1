<?php
/**
 * 供货商库:定义规则与具体的操作
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/18
 * Time:11:29
 *
 */
     
namespace App\Repositories\Product;

use App\Base\BaseRepository;
use App\Models\product\SupplierModel as Supplier;

class SupplierRepository extends BaseRepository
{
	//用于查询的字段
	protected $searchFields = ['name'];

    /**
     * 定义表单验证的规则
     * create   |   update
     */
    public $rules = [
            'create' => [   
                    'name' => 'required|max:128|unique:suppliers,name',
                    'address' => 'required|max:256',
                    'purchase_id' => 'required|numeric',
                    'url' => 'required|max:256|active_url',
                    'telephone' => 'required|max:256|digits_between:8,11'
            ],
            'update' => [   
                    'name' => 'required|max:128|unique:suppliers,name, {id}',
                    'address' => 'required|max:256',
                    'purchase_id' => 'required|numeric',
                    'url' => 'required|max:256|active_url',
                    'telephone' => 'required|max:256|digits_between:8,11'
            ]
    ];

	public function __construct(Supplier $supplier)
	{
		$this->model = $supplier;
	}
}