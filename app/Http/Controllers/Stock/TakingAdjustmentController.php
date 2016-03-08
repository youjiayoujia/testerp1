<?php
/**
 * 盘点调整控制器
 * 处理盘点调整相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 16/3/3
 * Time: 17:01am
 */

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\TakingAdjustmentModel;
use App\Models\ItemModel;

class TakingAdjustmentController extends Controller
{
    public function __construct(TakingAdjustmentModel $takingAdjustment)
    {
        $this->model = $takingAdjustment;
        $this->mainIndex = route('stockTakingAdjustment.index');
        $this->mainTitle = '盘点调整';
        $this->viewPath = 'stock.taking.adjustment.';
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
            'stockTakingForms' => $model->stockTakingForm,
            'stockouts' => $model->out,
            'stockins' => $model->in,
        ];

        return view($this->viewPath.'show', $response);
    }

    /**
     * 盘点调整审核页面 
     *
     * @param $id id号
     * @return view
     *
     */
    public function takingAdjustmentCheck($id)
    {
        $model = $this->model->find($id);
        $response = [
            'metas'=>$this->metas(__FUNCTION__),
            'model'=>$model,
            'stockTakingForms' => $model->stockTakingForm,
        ];

        return view($this->viewPath.'check', $response);
    }

    /**
     * 盘点调整审核 
     *
     * @param none
     * @return index
     *
     */
    public function takingAdjustmentCheckResult()
    {
        $id = request()->input('taking_id');
        if(request()->input('result') == 1) {
            $model = $this->model->find($id);
            $takingforms = $model->stockTakingForm;
            foreach($takingforms as $takingform) {
                if($takingform->stock_taking_status == 'equal') {
                    continue;
                }
                $item = ItemModel::find($takingform->stock->item_id);
                $warehousePositionId = $takingform->stock->warehouse_position_id;
                $quantity = abs($takingform->stock->all_quantity - $takingform->quantity);
                $amount = $quantity*$takingform->stock->unit_cost;
                $type = $takingform->stock_taking_status;
                $relation_id = $model->id;
                if($type == 'more') {
                    $type = 'INVENTORY_PROFIT';
                    $item->in($warehousePositionId, $quantity, $amount, $type, $relation_id);
                }
                if($type == 'less') {
                    $type = 'SHORTAGE';
                    $item->out($warehousePositionId, $quantity, $type, $relation_id);
                }
            }
            $model->update(['check_by'=>4, 'check_status'=>'1', 'check_time'=>date('Y-m-d h:m:s', time())]);
        }
        
        return redirect($this->mainIndex);
    }
}