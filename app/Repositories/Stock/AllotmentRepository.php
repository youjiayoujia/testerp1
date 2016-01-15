<?php
/**
 * 库存调拨类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:16/1/11
 * Time:11:12
 *
 */
namespace App\Repositories\Stock;

use App\Base\BaseRepository;
use App\Models\Stock\AllotmentModel;

class AllotmentRepository extends BaseRepository
{
    // 用于查询
    protected $searchFields = ['name'];

    // 规则验证
    public $rules = [
        'create' => [
            'adjust_form_id' => 'required',
            'stock_adjustments_id' => 'required|numeric'
        ],
        'update' => [
            'adjust_form_id' => 'required',
            'stock_adjustments_id' => 'required|numeric'
        ]
    ];
    
    public function __construct(AllotmentModel $allotment)
    {
        $this->model = $allotment;
    }

    /**
     * 删除执行的记录 
     *
     * @param $id integer 记录id
     * 
     * @return 
     *
     */
    public function destroy($id)
    {
        $obj = $this->get($id)->allotmentform;
        foreach($obj as $val)
            $val->delete();

        return $this->model->destroy($id);
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