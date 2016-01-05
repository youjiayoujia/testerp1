<?php
/**
 * 物流方式控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午6:28
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Logistics\SupplierRepository;
use App\Repositories\WarehouseRepository;
use App\Repositories\LogisticsRepository;

class LogisticsController extends Controller
{
    protected $logistics;

    public function __construct(Request $request, LogisticsRepository $logistics)
    {
        $this->request = $request;
        $this->logistics = $logistics;
        $this->mainIndex = route('logistics.index');
        $this->mainTitle = '物流方式';
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->logistics->auto()->paginate(),
        ];
        return view('logistics.index', $response);
    }

    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logistics' => $this->logistics->get($id),
        ];
        return view('logistics.show', $response);
    }

    public function create(SupplierRepository $supplier, WarehouseRepository $warehouse)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'supplier' => $supplier->all(),
            'warehouse' => $warehouse->all(),
        ];
        return view('logistics.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->logistics->rules('create'));
        $this->logistics->create($this->request->all());
        return redirect($this->mainIndex);
    }

    public function edit($id, SupplierRepository $supplier, WarehouseRepository $warehouse)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logistics' => $this->logistics->get($id),
            'supplier' => $supplier->all(),
            'warehouse' => $warehouse->all(),
        ];
        return view('logistics.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->logistics->rules('update', $id));
        $this->logistics->update($id, $this->request->all());
        return redirect($this->mainIndex);
    }

    public function destroy($id)
    {
        $this->logistics->destroy($id);
        return redirect($this->mainIndex);
    }

}