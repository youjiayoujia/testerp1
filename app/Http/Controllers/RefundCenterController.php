<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order\RefundModel;
use App\Models\PaypalsModel;
use App\Modules\Paypal\PaypalApi;
use App\Models\CurrencyModel;



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
        //
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
        //
        dd(request()->input());
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
}
