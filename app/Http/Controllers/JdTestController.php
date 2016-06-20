<?php

namespace App\Http\Controllers;
use App\Models\JdTest\Test1Model;

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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);

    }
    public function edit($id)
    {
        $row = $this->model->find($id);
        if(!$row){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = array(
            'metas' => $this->metas(__FUNCTION__),
            'model' => $row
        );
        return view($this->viewPath . 'edit',$response);
    }
}
