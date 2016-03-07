<?php
/**
 * 盘点控制器
 * 处理盘点相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 16/3/3
 * Time: 10:04am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\TakingModel;
use App\Models\Stock\TakingFormModel;
use App\Models\Stock\TakingAdjustmentModel;

class TakingController extends Controller
{
    public function __construct(TakingModel $taking)
    {
        $this->model = $taking;
        $this->mainIndex = route('stockTaking.index');
        $this->mainTitle = '盘点';
        $this->viewPath = 'stock.taking.';
    }

    /**
     * 列表展示 
     *
     * @param $id id号
     * @return view
     *
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'stockTakingForms'=>$model->stockTakingForm,
        ];

        return view($this->viewPath.'show', $response);
    }

    /**
     * 信息编辑 
     *
     * @param $id id号
     * @return view
     *
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        $response= [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'takingForms' => $model->stockTakingForm
        ];

        return view($this->viewPath.'edit', $response);
    }

    /**
     * 信息更新 
     *
     * @param $id id号
     * @return index
     *
     */
    public function update($id)
    {
        request()->flash();
        $takings = request()->all();
        $arr = $takings['arr'];
        $len = count($arr['id']);
        $this->model->find($id)->update(['stock_taking_by'=>'1', 'stock_taking_time'=>date('Y-m-d h:m:s',time())]);
        $flag = 1;
        for($i=0;$i<$len;$i++)
        {
            $buf = [];
            foreach($arr as $key => $val)
            {
                $buf[$key] = $val[$i];
            }
            if($buf['quantity'] == '') {
                $flag = 0;
                continue;
            }
            $taking = TakingFormModel::find($buf['id']);
            if($taking->stock) {
                if($taking->stock->all_quantity == $buf['quantity']) {
                    $taking->update(['quantity'=>$buf['quantity'], 'stock_taking_status'=>'equal', 'stock_taking_yn'=>'0']);
                }
                if($taking->stock->all_quantity < $buf['quantity']) {
                    $taking->update(['quantity'=>$buf['quantity'], 'stock_taking_status'=>'more', 'stock_taking_yn'=>'1']);
                }
                if($buf['quantity'] < $taking->stock->all_quantity && $taking->stock->all_quantity - $buf['quantity'] <= $taking->stock->available_quantity) {
                    $taking->update(['quantity'=>$buf['quantity'], 'stock_taking_status'=>'less', 'stock_taking_yn'=>'1']);
                }
                if($buf['quantity'] < $taking->stock->all_quantity && $taking->stock->all_quantity - $buf['quantity'] > $taking->stock->available_quantity) {
                    $taking->update(['quantity'=>$buf['quantity'], 'stock_taking_status'=>'less', 'stock_taking_yn'=>'0']);
                    $flag = 0;
                }
            }
        }
        $flag ? $this->model->find($id)->update(['create_taking_adjustment' => '1']) : $this->model->find($id)->update(['create_taking_adjustment' => '0']);
        return redirect($this->mainIndex);
    }

    /**
     * ajax审核生成盘点表 
     *
     * @param none 
     * @return json
     *
     */
    public function ajaxTakingCheck()
    {
        if(request()->ajax()) {
            $id = request()->input('id');
            $model = $this->model->find($id);
            $model->stockTakingAdjustment ? '' :$model->stockTakingAdjustment()->create(['adjustment_by'=>'3', 'adjustment_time'=>date('Y-m-d h:m:s', time())]);
            return json_encode('11');
        }
        
        return json_encode('false');
    }
}