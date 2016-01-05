<?php
/**
 * 物流商控制器
 *
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
        $this->mainIndex = route('logisticsSupplier.index');
        $this->mainTitle = '物流商';
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->supplier->auto()->paginate(),
        ];
        return view('logistics.supplier.index', $response);
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view('logistics.supplier.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->supplier->rules('create'));
        $this->supplier->create($this->request->all());
        return redirect($this->mainIndex);
    }

    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'supplier' => $this->supplier->get($id),
        ];
        return view('logistics.supplier.show', $response);
    }

    public function edit($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'supplier' => $this->supplier->get($id),
        ];
        return view('logistics.supplier.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->supplier->rules('update', $id));
        $this->supplier->update($id, $this->request->all());
        return redirect($this->mainIndex);
    }

    public function destroy($id)
    {
        $this->supplier->destroy($id);
        return redirect($this->mainIndex);
    }

}