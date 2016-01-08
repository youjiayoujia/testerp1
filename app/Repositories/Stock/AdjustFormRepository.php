<?php
/**
 * 库存调整id类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:16/1/7
 * Time:14:06
 *
 */
namespace App\Repositories\Stock;

use App\Base\BaseRepository;
use App\Models\Stock\AdjustFormModel;

class AdjustFormRepository extends BaseRepository
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
    
    public function __construct(AdjustFormModel $adjust)
    {
        $this->model = $adjust;
    }

    /**
     * 通过单号获取对应的sku记录 
     *
     * @param $arr 
     * @return 对象数组
     *
     */
    public function get_adjustment($arr)
    {
        return $this->model->where($arr)->get();
    }

    /**
     * 删除指定id资源
     *
     * @param  int $id 资源id
     * @return bool
     *
     */
    public function destroy($id)
    {
        $obj = $this->get($id)->adjustment;
        foreach($obj as $val)
            $val->delete();

        return $this->model->destroy($id);
    }
}