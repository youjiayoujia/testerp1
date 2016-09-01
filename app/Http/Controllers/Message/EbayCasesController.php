<?php
/**
 * Ebay case纠纷
 * @author jiangdi
 *
 */

namespace App\Http\Controllers\Message;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Message\Issues\EbayCasesListsModel;
use App\Modules\Channel\Adapter\EbayAdapter;

class EbayCasesController extends Controller
{
    public function __construct(EbayCasesListsModel $caselist)
    {
        $this->model = $caselist;
        $this->mainIndex = route('ebayCases.index');
        $this->mainTitle = 'Ebay Channel Cases';
        $this->viewPath = 'message.ebay_cases.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'metas'    => $this->metas(__FUNCTION__),
            'data'     => $this->autoList($this->model),
            'status'   => $this->model->distinct()->get(['status']),
            'types'     => $this->model->distinct()->get(['type']),
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
        $data = $this->model->find($id);
        if(empty($data)){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        if($data->process_status != 'COMPLETE' && $data->process_status != 'PROCESS'){
            $data->process_status = 'PROCESS';
            $data->save();
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'case'  => $data,
            'case_order_info' => $data->CaseOrderInfo

        ];
        return view($this->viewPath . 'edit',$response);
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
     * case回复：给用户回复消息
     */
    public function MessageToBuyer(){
        $request = request()->input();
        $ebay = new EbayAdapter($this->model->find(request()->input('id'))->account->ApiConfig);
        $case_obj = $this->model->find($request['id']);
        $caseAry['caseType'] = $case_obj->type;
        $caseAry['caseId'] = $case_obj->id;
        $caseAry['messageToBuyer'] = trim($request['messgae_content']);

        if($ebay->offerOtherSolution($caseAry)){
            $case_obj->assign_id = request()->user()->id;
            $case_obj->process_status = 'COMPLETE';
            $case_obj->save();
            return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . '处理成功.'));

        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '处理失败.'));
        }
    }

    /**
     * case回复：提供客户追踪信息
     */
    public function AddTrackingDetails(){

        $request = request()->input();
        $ebay = new EbayAdapter($this->model->find($request['id'])->account->ApiConfig);
        $case_obj = $this->model->find($request['id']);

        $caseId = $case_obj->id;
        $caseType = $case_obj->type;
        $comments = $request['comments'];


        if($request['is_tracked'] == 10){
            $trackingNumber = $request['trackingNumber'];
            $carrierUsed = $request['carrier'];
            $result = $ebay->provideTrackingInfo(compact('caseId','caseType','comments','trackingNumber','carrierUsed'));

        }else{
            $carrierUsed = $request['carrierUsed'];
            $shippedDate = $request['shippedDate'];
            $result = $ebay->provideTrackingInfo(compact('caseId','caseType','comments','shippedDate','carrierUsed'));
        }

        if($result){
            $case_obj->assign_id = request()->user()->id;
            $case_obj->process_status = 'COMPLETE';
            $case_obj->save();
            return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . '处理成功.'));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '处理失败.'));
        }
    }

    /**
     * case 回复： 退款
     */
    public function RefundBuyer(){
        echo '退款';
    }


}
