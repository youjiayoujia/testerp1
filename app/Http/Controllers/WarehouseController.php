<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/3
 * Time: 下午3:33
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\WarehouseRepository;

class WarehouseController extends Controller
{
    protected $warehouse;

    public function __construct(Request $request, WarehouseRepository $warehouse)
    {
        $this->request = $request;
        $this->warehouse = $warehouse;
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'columns' => $this->warehouse->columns,
            'data' => $this->warehouse->index($this->request),
        ];

        return view('warehouse.index', $response);
    }

    public function create()
    {
        $response = [

        ];

        return view('warehouse.create', $response);
    }

    public function store(Request $request)
    {
        $this->request->flash();
        $this->validate($this->request, $this->warehouse->rules);
        $this->warehouse->store($this->request);

        return redirect(route('warehouse.index'));
    }

    public function show($id)
    {
        $response = [
            'warehouse' => $this->warehouse->detail($id),
        ];

        return view('warehouse.show', $response);
    }

    public function edit($id)
    {
        $response = [
            'warehouse' => $this->warehouse->edit($id),
        ];

        return view('warehouse.edit', $response);
    }

    public function update(Request $request, $id)
    {
        $rules = $this->warehouse->rules;
        $rules['warehouse_name'] .= $id;
        $this->request->flash();
        $this->validate($this->request, $rules);
        $this->warehouse->update($id, $this->request);

        return redirect(route('warehouse.index'));
    }

    public function destroy($id)
    {
        $this->warehouse->destroy($id);

        return redirect(route('warehouse.index'));
    }

}