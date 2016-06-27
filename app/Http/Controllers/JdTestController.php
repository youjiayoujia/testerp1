<?php

namespace App\Http\Controllers;
use App\Models\JdTest\Test1Model;
use App\Modules\Channel\ChannelModule;
use App\Models\Message\AccountModel;
use App\Models\OrderModel;
use App\Models\Message\MessageModel;
use DB;

class JdTestController extends Controller
{
    public function __construct(Test1Model $test1)
    {
        $this->model     = $test1;
        $this->mainIndex = route('test1.index');
        $this->mainTitle = '测试分类1';
        $this->viewPath  = 'jdtest.';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $number = [17];
        $message = new MessageModel();
        $data = $message->setRelatedOrders($number);
var_dump($data);exit;
        //$order = OrderModel::ofOrdernum($number)->first();

        return view($this->viewPath . 'index');


    }

}
