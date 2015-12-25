<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午6:28
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Repositories\LogisticsRepository;

class LogisticsController extends Controller
{
    protected $logistics;

    public function __construct(Request $request, LogisticsRepository $logistics)
    {
        $this->request = $request;
        $this->logistics = $logistics;
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'data' => $this->logistics->auto()->paginate(),
        ];
        return view('logistics.index', $response);
    }

    public function show($id)
    {
        $response = [
          'logistics' => $this->logistics->get($id),
        ];
        return view('logistics.show', $response);
    }

    public function create()
    {
        $response = [
            'supplier' => $this->logistics->getSupplier(),
            'warehouse' => $this->logistics->getWarehouse(),
        ];
        return view('logistics.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->logistics->rules('create'));
        $this->logistics->create($this->request->all());
        return redirect(route('logistics.index'));
    }

    public function edit($id)
    {
        $response = [
            'logistics' => $this->logistics->get($id),
            'supplier' => $this->logistics->getSupplier(),
            'warehouse' => $this->logistics->getWarehouse(),
        ];
        return view('logistics.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->logistics->rules('update', $id));
        $this->logistics->update($id, $this->request->all());
        return redirect(route('logistics.index'));
    }

    public function destroy($id)
    {
        $this->logistics->destroy($id);
        return redirect(route('logistics.index'));
    }

}