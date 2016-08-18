<?php
/**
 * 售后服务控制器
 * @author haiou 2016/7/27
 */
namespace App\Http\Controllers\Publish\Smt;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\Publish\Smt\afterSalesService;

class AfterSalesServiceController extends Controller
{
    public function __construct(afterSalesService $afterSalesServiceModel){
        $this->model = $afterSalesServiceModel;
    }

    
    /**
     * 异步获取速卖通售后服务模板列表,返回下拉框的选项，方便调用统一接口
     */
    public function ajaxSmtAfterServiceList(){
        //账号
        $token_id = Input::get('token_id');    
        if ($token_id){
            $data = $this->model->where(['plat' => 6, 'token_id' => $token_id])->get();
            $options = '';
            if ($data){
                foreach ($data as $row){
                    $options .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                }
            }
            unset($data);
            $this->ajax_return('', true, $options);
        }else {
            $this->ajax_return('账号错误', 'false');
        }
    }
    
    //json返回数据结构
    public function ajax_return($info='', $status=1, $data='') {
        $result = array('data' => $data, 'info' => $info, 'status' => $status);
        exit( json_encode($result) );
    }
    
}
