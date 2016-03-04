<?php

namespace App\Http\Controllers\Product\Channel;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AmazonController extends Controller
{

    public function __construct()
    {
        $this->mainIndex = route('amazonProduct.index');
        $this->mainTitle = '亚马逊选中产品';
        $this->viewPath = 'product.channel.amazon.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {print_r(__FUNCTION__);exit;
        echo 111;exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo 222;exit;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        echo 933;exit;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo 8374;
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
        echo 8374;exit;
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
     * 产品选中
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function beChosed()
    {
        $product_ids = request()->input('product_ids');
        $channel_id = request()->input('channel_id');
        //print_r(__FUNCTION__);exit;
        $response = [
            'metas' => $this->metas('index'),
        ];

        return view( $this->viewPath .'index', $response);

    }
}
