<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order\RefundModel;
use App\Models\PaypalsModel;
use App\Modules\Paypal\PaypalApi;


class RefoundCenterController extends Controller
{
    public function __construct(RefundModel $refund)
    {
        $this->model = $refund;
        $this->mainIndex = route('refoundCenter.index');
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
        dd($refund->SKUs);
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

        $refund = $this->model->find($form['id']);
        dd($form);


        $newArray                  = array();

        $newArray['TRANSACTIONID'] = 'TRANSACTIONID 参数';
        $newArray['REFUNDTYPE']    = ( $refund['refundType'] === 'FULL' ) ? 'Full' : 'Partial';
        $newArray['CURRENCYCODE']  = $refund['refund_currency'];
        $newArray['NOTE']          = $refund['memo'];
        if ( $refund['refundType'] == 'PARTIAL' ) {
            $newArray['AMT'] = $refund['refund_amount'];
        }




        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    }
}
