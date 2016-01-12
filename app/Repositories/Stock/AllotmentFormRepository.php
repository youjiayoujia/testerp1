<?php
/**
 * 库存调整详细类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:16/1/12
 * Time:10:50
 *
 */
namespace App\Repositories\Stock;

use App\Base\BaseRepository;
use App\Models\Stock\AllotmentFormModel;

class AllotmentFormRepository extends BaseRepository
{
    // 用于查询
    protected $searchFields = ['name'];
    
    public function __construct(AllotmentFormModel $allotment)
    {
        $this->model = $allotment;
    }

    /**
     * 通过$arr获取指定的记录的指定列
     *
     * @param $arr ,$field那些列
     * @return 数组
     *
     */
    public function getObj($arr, $field=['*'])
    {
        return $this->model->where($arr)->get($field);
    }
}