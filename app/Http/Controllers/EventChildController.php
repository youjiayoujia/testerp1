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
            if($to) {
                $to = $to->toarray();
            }
            $from = unserialize(base64_decode($model->from_arr));
            if($from) {
                $from = $from->toarray();
            }
            if(!$from) {
                $html .= "<div class='panel panel-default'>
                        <div class='panel-heading'>备注:".$model->what. '&nbsp;&nbsp;&nbsp;&nbsp;操作时间:' . $model->when . "&nbsp;&nbsp;&nbsp;&nbsp;操作人:". $model->who . "</div>
                        <div class='panel-body'>";
                foreach($to as $key => $value) {
                    $html .= "<div class='form-control col-lg-6'>to['".$key."']<span class='glyphicon glyphicon-arrow-right'></span>";
                    if(is_array($value)) {
                        if(count($value) == count($value,1)) {
                            foreach($value as $k => $v) {
                                $html .= "to['".$key."']['".$k."']<span class='glyphicon glyphicon-arrow-right'></span>"+$v+"&nbsp;&nbsp;&nbsp;&nbsp;";
                            }
                        } else {
                            foreach($value as $k => $v) {
                                foreach($v as $k1 => $v1) {
                                    $html .= "to['".$key."']['".$k."']<span class='glyphicon glyphicon-arrow-right'></span>"+$v1+"&nbsp;&nbsp;&nbsp;&nbsp;";
                                }
                            }
                        }
                    } else {
                        $html .= $value;
                    }
                    $html .= "</div>";
                }  
                $html .= "</div></div>";
                continue;     
            }
            $html .= "<div class='panel panel-default'>
                        <div class='panel-heading'>备注:".$model->what. '&nbsp;&nbsp;&nbsp;&nbsp;操作时间:' . $model->when . "&nbsp;&nbsp;&nbsp;&nbsp;操作人:". $model->who . "</div>
                        <div class='panel-body'>";
            $flag = 1;
            $this->calcTwoArr($from,$to);
            foreach($from as $key => $value) {
                $html .= "<div class='row'>from['".$key."']<span class='glyphicon glyphicon-arrow-right'></span>";
                if(is_array($value)) {
                    foreach($value as $k => $v) {
                        $html .="<div class='row'>";
                        foreach($v as $k1 => $v1) {
                            $html .= "from['".$key."']['".$k."']['".$k1."']<span class='glyphicon glyphicon-arrow-right'></span>".$v1."&nbsp;&nbsp;&nbsp;&nbsp;";
                        }
                        $html .="</div>";
                    }
                } else {
                    $html .= $value;
                }
                $html .= "</div>";
            }
            $html .= "<hr/>";
            foreach($to as $key => $value) {
                $html .= "<div class='row'>to['".$key."']<span class='glyphicon glyphicon-arrow-right'></span>";
                if(is_array($value)) {
                    foreach($value as $k => $v) {
                        $html .="<div class='row'>";
                        foreach($v as $k1 => $v1) {
                            $html .= "to['".$key."']['".$k."']['".$k1."']<span class='glyphicon glyphicon-arrow-right'></span>".$v1."&nbsp;&nbsp;&nbsp;&nbsp;";
                        }
                        $html .="</div>";
                    }
                } else {
                    $html .= $value;
                }
                $html .= "</div>";
            }
            $html.= '</div></div>';
        }

        return $html;
    }
}