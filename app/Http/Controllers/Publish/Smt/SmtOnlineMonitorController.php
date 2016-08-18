<?php

namespace App\Http\Controllers\Publish\Smt;

use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtProductSku;

class SmtOnlineMonitorController extends Controller
{
    public function __construct(smtProductSku $smtProductSku){
        $this->viewPath = "publish.smt.";  
        $this->model = $smtProductSku;
        $this->mainIndex = route('smt.index');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->mainTitle='速卖通在线数量监控';
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'onlineMonitor', $response);
    }

}
