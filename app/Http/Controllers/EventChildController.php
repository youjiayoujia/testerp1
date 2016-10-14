<?php
/**
 * 汇率控制器
 * 处理汇率相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event\ChildModel;
use App\Models\Event\CategoryModel;

class EventChildController extends Controller
{
    public function __construct(ChildModel $child)
    {
        $this->model = $child;
        $this->mainIndex = route('eventChild.index');
        $this->mainTitle = '事件记录';
        $this->viewPath = 'event.child.';
    }

    /**
     *  返回对应的操作日志,ajax请求 
     *
     *  @param none
     *  @return html
     *
     */
    public function getInfo()
    {
        $table = request('table');
        $id = request('id');
        $category = CategoryModel::where('model_name', $table)->first();
        if(!$category) {
            return false;
        }
        $models = $category->child()->where('type_id', $id)->get()->sortByDesc('when');
        $html = '';
        foreach($models as $model) {
            $to = unserialize(base64_decode($model->to_arr));
            $from = unserialize(base64_decode($model->from_arr));
            $toFillable = $to->fillable;
            if(!$from) {
                $html .= "<div class='panel panel-default'>
                        <div class='panel-heading'>备注:".$model->what. '&nbsp;&nbsp;&nbsp;&nbsp;操作时间:' . $model->when . "&nbsp;&nbsp;&nbsp;&nbsp;操作人:". $model->who . "</div>
                        <div class='panel-body'>";
                foreach($toFillable as $key => $value) {
                    $html .= "<div class='row'>";
                    $html .= $value . "&nbsp;&nbsp;:&nbsp;&nbsp;" . $to->$value;
                    $html .= "</div>";
                }               
                $html .= "</div></div>";   
                continue;     
            }
            $fromFillable = $from->fillable;
            $html .= "<div class='panel panel-default'>
                        <div class='panel-heading'>备注:".$model->what. '&nbsp;&nbsp;&nbsp;&nbsp;操作时间:' . $model->when . "&nbsp;&nbsp;&nbsp;&nbsp;操作人:". $model->who . "</div>
                        <div class='panel-body'>";
            $flag = 1;
            foreach($toFillable as $key => $value) {
                if($to->$value == $from->$value) {
                    continue;
                }
                $flag = 0;
                $html .= "<div class='row'>";
                $html .= $value . "&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;" . $from->$value . "&nbsp;&nbsp;&nbsp;&nbsp;<span class='glyphicon glyphicon-arrow-right'></span>&nbsp;&nbsp;&nbsp;&nbsp;" . $to->$value;
                $html .= "</div>";
            }      
            if($flag) {
                $html .= "<div class='row'>数据无变化</div>";
            }         
            $html .= "</div></div>";
        }

        return $html;
    }
}