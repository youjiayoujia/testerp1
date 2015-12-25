<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:42
 */

namespace App\Http\Controllers\Logistics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Logistics\SupplierRepository;

class SupplierController extends Controller
{
    protected $supplier;

    public function __construct(Request $request, SupplierRepository $supplier)
    {
        $this->request = $request;
        $this->supplier = $supplier;
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'data' => $this->supplier->auto()->paginate(),
        ];
        return view('logistics.logisticsSupplier.index', $response);
    }

    public function create()
    {
        return view('logistics.logisticsSupplier.create');
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->supplier->rules('create'));
        $this->supplier->create($this->request->all());
        return redirect(route('logisticsSupplier.index'));
    }

    public function show($id)
    {
        $response = [
            'supplier' => $this->supplier->get($id),
        ];
        return view('logistics.logisticsSupplier.show', $response);
    }

    public function edit($id)
    {
        $response = [
            'supplier' => $this->supplier->get($id),
        ];
        return view('logistics.logisticsSupplier.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->supplier->rules('update', $id));
        $this->supplier->update($id, $this->request->all());
        return redirect(route('logisticsSupplier.index'));
    }

    public function destroy($id)
    {
        $this->supplier->destroy($id);
        return redirect(route('logisticsSupplier.index'));
    }

}