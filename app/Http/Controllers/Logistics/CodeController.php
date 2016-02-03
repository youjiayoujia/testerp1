<?php
/**
 * 跟踪号控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/28
 * Time: 上午10:50
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\CodeModel as CodeModel;
use App\Models\LogisticsModel as LogisticsModel;
use Input;
use App;
use Redirect;
use DB;


class codesImport extends \Maatwebsite\Excel\Files\ExcelFile {

    protected $delimiter  = ',';
    protected $enclosure  = '"';
    protected $lineEnding = '\r\n';

    public function getFile()
    {
        $file = Input::file('report');
        $filename = $this->doSomethingLikeUpload($file);
        return $filename;
    }

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }

}

class CodeController extends Controller
{
    public function __construct(CodeModel $channel)
    {
        $this->model = $channel;
        $this->mainIndex = route('logisticsCode.index');
        $this->mainTitle = '跟踪号';
        $this->viewPath = 'logistics.code.';
    }


    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logisticses'=>$this->getLogistics(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'logisticses'=>$this->getLogistics(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function getLogistics(){

        return LogisticsModel::all();
    }

    //导入
    public function batchAddTrCode($logistic_id)
    {
        $logistic = LogisticsModel::find($logistic_id);
        $meta = [
            "mainIndex" => "http://www.chenxuewenerp.com/logisticsCode",
            "mainTitle" => "物流方式列表",
            "title" => "导入号码池",
        ];
        $response = [
            'metas' => $meta,
            'logistic' => $logistic,
        ];
        return view($this->viewPath . 'batchadd', $response);
    }

    public function batchAddTrCodeFn()
    {

        //public_path();
        //app_path();

        $logistic_id = Input::get('logistic_id', '');
        if(!$logistic_id){
            return Redirect::to('logistics')->with('alert', $this->alert('danger', '未选择物流方式！'));
        }

        // 保存上传文件
        if (Input::hasFile('trackingnos')) {
            if (Input::file('trackingnos')->isValid()) {
                $file = Input::file('trackingnos');
                $destinationPath = public_path() . '/uploads/logistics/codes';
                $fileName = date("Y-m-d", time()) . '-' . rand(100, 999) . '-' . $file->getClientOriginalName();
                Input::file('trackingnos')->move($destinationPath, $fileName);
            }else{
                return Redirect::to('batchAddTrCode/'.$logistic_id)->with('alert', $this->alert('danger', '文件非法！'));
            }
        }else{
            return Redirect::to('batchAddTrCode/'.$logistic_id)->with('alert', $this->alert('danger', '未上传任何文件！'));
        }

        //写操作
        $codes = DB::table('logistics_codes')->lists('code');  //获取已经取得的物流号，用于后面的筛选

        $successNumber = 0;
        $repeatNumber = 0;
        $repeatCodes = [];
        $baseSql = "INSERT INTO logistics_codes (logistics_id,code,created_at,updated_at) VALUES";
        $valuesStr = "";
        if (($handle = fopen($destinationPath.'/'.$fileName, "r")) !== FALSE) {
            $created_at = null;
            while (($data = fgetcsv($handle, ",")) !== FALSE) {
                if(in_array($data[0], $codes)){
                    $repeatCodes[] = $data[0];
                    $repeatNumber++;
                }else{
                    $valuesStr .= "(".$logistic_id.",'".$data[0]."',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP),";
                    $successNumber++;
                }
            }
            fclose($handle);
            if($valuesStr != ""){
                $sql = $baseSql.$valuesStr;
                $sql = substr($sql,0,strlen($sql)-1); //去除最后一个value的逗号
                DB::statement($sql);
            }

            $totalNumber = $successNumber + $repeatNumber;
            $content = "本次共选择导入".$totalNumber."个跟踪号,成功导入".$successNumber."个,有".$repeatNumber."个重复未导入,如下：";
            foreach($repeatCodes as $repeatCode){
                $content .= $repeatCode.",";
            }
            $content = substr($content,0,strlen($content)-1);
            return Redirect::to('logisticsCode')->with('alert', $this->alert('success', $content));
        }else{
            return Redirect::to('logisticsCode')->with('alert', $this->alert('danger', '上传失败！'));
        }
    }

    //扫描
    public function scanAddTrCode($logistic_id)
    {
        $logistic = LogisticsModel::find($logistic_id);
        $meta = [
            "mainIndex" => "http://www.chenxuewenerp.com/logisticsCode",
            "mainTitle" => "物流方式列表",
            "title" => "扫描-号码池",
        ];
        $response = [
            'metas' => $meta,
            'logistic' => $logistic,
        ];
        return view($this->viewPath . 'scanadd', $response);
    }
}