<?php

/*namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{

    protected $brand;

    public function __construct(Request $request, BrandRepository $brand)
    {
        $this->request = $request;
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->request->flash();
        $response = [
            'columns' => $this->brand->columns,
            'data' => $this->brand->index($this->request),
        ];

        return view('brand.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = [

        ];

        return view('brand.create', $response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->request->flash();
        $this->validate($this->request, $this->brand->rules);
        $this->brand->store($this->request);
        return redirect(route('brand.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = [
            'brand' => $this->brand->detail($id),
        ];

        return view('brand.show', $response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [
            'brand' => $this->brand->edit($id),
        ];
        return view('brand.edit', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = $this->brand->rules;
        $rules['brand_name'] .= $id;
        $this->request->flash();
        $this->validate($this->request,$rules);
        $this->brand->update($id, $this->request);
        return redirect(route('brand.index'));       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->brand->destroy($id);
        return redirect(route('brand.index'));
    }
}
