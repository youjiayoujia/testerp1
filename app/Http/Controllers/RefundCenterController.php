<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order\RefundModel;
use App\Models\PaypalsModel;
use App\Modules\Paypal\PaypalApi;
use App\Models\CurrencyModel;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;
use App\Models\ChannelModel;
use Excel;
use App\Models\Channel\AccountModel;


class RefundCenterController extends Controller
{
    public function __construct(RefundModel $refund)
    {
        $this->model = $refund;
        $this->mainIndex = route('refundCenter.index');
        $this->mainTitle = '退款中心';
        $this->viewPath = 'refund.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $response = [
            'metas'   => $this->metas(__FUNCTION__),
            'data'    => $this->autoList($this->model),
            'paypals' => PaypalsModel::where('is_enable','=','1')->get(),
            'users'   => UserModel::all(),
            'channels'=> ChannelModel::all(),
            'types'    => config('refund.type'),
           // 'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index',$response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];
        return view($this->viewPath . 'show', $response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $refund = $this->model->find($id);
        $currency = CurrencyModel::all();
        return view($this->viewPath . 'edit',compact('refund','currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $form = request()->all();
        if(isset($form['image'])){
            if(!empty($model->image)){
                Storage::delete(str_replace('uploads/','',$model->image));
            }
            $file = request()->file('image');
            if($file->isValid()){
                $entension = $file->getClientOriginalExtension(); //上传文件的后缀.
                if($entension == 'php'){
                    return redirect($this->mainIndex)->with('alert', $this->alert('danger', '上传文件类型不被允许.'));
                }
                $path = config('refund.image_path').$model->order_id;
                $image_name = '/'.time().'.'.$file->getClientOriginalExtension();
                $file->move($path , $image_name);

                $form['image'] = 'uploads/refund/' . $model->order_id . $image_name;
            }else{

            }
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update($form);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '操作成功.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * paypal退款
     */
    public function doPaypalRefund(){
        $form = request()->input();
        if(!empty($form['paypal_id']) && !empty($form['password']) && !empty($form['id'])){
            $paypal = PaypalsModel::find($form['paypal_id']);
            if($paypal->paypal_paddword == $form['password']){
                $refund = $this->model->find($form['id']);
                $refund->Order->transaction_number;
                $paramAry = array();
                $paramAry['TRANSACTIONID'] = $refund->Order->transaction_number;
                $paramAry['REFUNDTYPE']    = ( $refund['refundType'] === 'FULL' ) ? 'Full' : 'Partial';
                $paramAry['CURRENCYCODE']  = $refund['refund_currency'];
                if(!empty($refund['memo'])){
                    $paramAry['NOTE'] = $refund['memo'];
                }
                if ( $refund['refundType'] == 'PARTIAL' ) {
                    $paramAry['AMT'] = $refund['refund_amount'];
                }
                $paypalApi = new PaypalApi($paypal->ApiConfig);
                if($paypalApi->apiRefund($paramAry)){
                    $refund->process_status = 'COMPLETE';
                    $refund->save();
                    return redirect($this->mainIndex)->with('alert', $this->alert('success', '退款成功！'));
                }else{
                    return redirect($this->mainIndex)->with('alert', $this->alert('danger', '退款失败，请联系IT！'));
                }
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger', 'Paypal密码错误'));
            }
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '参数不完整'));
        }

    }
    
    public function batchProcessStatus(){
        $form = request()->input();
        if(!empty($form['process']) && !empty($form['ids'])){
            if($this->model->batchProcess($form)){
                 return 10;
            }
        }
        return -10;
    }

    public function RefundCsvFormat(){
        $rows = [
            [
                'Refund No.' => '例：450098',
                '退款凭证(注意：退款成功填凭证，失败不填)'     => '9VR69784B8555501V',
            ]
        ];

        $this->exportExcel($rows, 'refundMoneyTemplate');
    }
    public function  exportExcel($rows,$name){
        Excel::create($name, function($excel) use ($rows){
                $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    //导出财务退
    public function financeExport() {
        $form = request()->input();
        $data = [];
        if(isset($form['channel']) && isset($form['process'])){
            $data[] = [
                'Refund NO.',
                '内单号',
                '渠道',
                '买家ID',
                'SKU',
                '退款金额',
                '交易凭证',
                '退款原因',
                '客服',
                '状态',
                '录入日期',
                '客户Paypal帐号'
            ];
            $refunds = $this->model->where('channel_id','=',$form['channel'])->where('process_status','=',$form['process'])->get();

            if(!$refunds->isEmpty()){
                foreach ($refunds as $refund){
                    $data[] =[
                        $refund->id,
                        $refund->order_id,
                        $refund->ChannelName,
                        $refund->Order->by_id,
                        $refund->SKUs,
                        $refund->refund_amount,
                        $refund->PaidTime,
                        $refund->ReasonName,
                        $refund->CustomerName,
                        $refund->ProcessStatusName,
                        $refund->created_at,
                        $refund->user_paypal_account,
                    ];
                }
            }

            Excel::create('moneyBackManageExport'.date('Y-m-d'), function($excel) use ($data){
                $excel->sheet('', function($sheet) use ($data){
                    $sheet->fromArray($data);
                });
            })->download('csv');
        }
    }
    public function changeReundNoteStatus(){
        // $file = request()->file('excel');
        $result_array   = [];

        if(isset($_FILES['excel']['tmp_name'])){
            $refund_array = Excel::load($_FILES['excel']['tmp_name'],'gb2312')->noHeading()->toArray();
            if(is_array($refund_array) && count($refund_array) >= 2){
                unset($refund_array[0]);
                if(strpos($refund_array[1][1],':')){
                    unset($refund_array[1]);
                }
                foreach($refund_array as $refund){
                    $refund_object = RefundModel::find($refund[1]);
                    if(!empty($refund_object)){
                        if(!empty($refund[2])){ //退款成功
                            $refund_object->refund_voucher = $refund[2];
                            $refund_object->process_status = 'COMPLETE';
                            $result_array[] = [
                                'id'     => $refund[1],
                                'status' => 1
                            ];
                        }else{ //失败
                            $refund_object->process_status = 'FAILED';
                            $result_array[] = [
                                'id'     => $refund[1],
                                'status' => -1,
                            ];
                        }
                        $refund_object->save();
                    }
                }
                return redirect($this->mainIndex)->with('alert', $this->alert('success', '数据导入成功'));
            }

        }
        return redirect($this->mainIndex)->with('alert', $this->alert('danger', '数据导入失败'));

    }

    /**
     * 退款统计报表
     */
    public function refundStatistics(){

        $metas = [
            'mainIndex' => route('feeback.feedBackStatistics'),
            'mainTitle' => '报表',
            'title'     => '退款统计',
        ];



        $response = [
            'metas' => $metas,
            'channels' => ChannelModel::all(),
            //'data'  => $total,
        ];

        return view($this->viewPath . 'statistics',$response);
    }

    public function getChannelAccount(){
        $channel_id = request()->input('channel_id');
        $accounts   = AccountModel::where('channel_id',$channel_id)->get(['account','id'])->toJson();
        echo $accounts;
    }









    
}
