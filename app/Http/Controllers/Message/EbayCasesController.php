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
        //
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),

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



        $data->CaseContent;

        if(empty($data)){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'case'  => $data,

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
}