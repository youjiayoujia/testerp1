<?php

/**
 * 选款需求库 
 * 定义规则与具体操作
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/18
 * Time:17:26
 */

namespace App\Repositories\Product;

use App\Base\BaseRepository;
use App\Models\Product\productRequireModel as productRequire;

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
     * 文件移动
     * @param $fd file 文件指针
     * @param $name 转移后的文件名
     * @param $path 转移路径
     * @return 转以后的文件路径
     *
     */
    function move_file($fd, $name, $path)
    {
        $dstname = $name.'.'.$fd->getClientOriginalExtension();
        file_exists($path) or mkdir($path, 644, true);
        if(file_exists($path.'/'.$dstname))
            unlink($path.'/'.$dstname);
        $fd->move($path,$dstname);

        return "/".$path."/".$dstname;
    }
}