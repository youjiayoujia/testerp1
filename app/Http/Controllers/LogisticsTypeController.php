<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/17
 * Time: 下午3:48
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Repositories\LogisticsTypeRepository;

class LogisticsTypeController extends Controller
{
    protected $logisticsType;

    public function __construct(Request $request, LogisticsTypeRepository $logisticsType)
    {
        $this->request = $request;
        $this->logisticsType = $logisticsType;
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'data' => $this->logisticsType->auto()->paginate(),
        ];
        return view('logisticsType.index', $response);
    }

    public function create()
    {
        $response = [
            'logisticsType' => $this->logisticsType->getLogistics(),
        ];
        return view('logisticsType.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->logisticsType->rules('create'));
        $this->logisticsType->create($this->request->all());
        return redirect(route('logisticsType.index'));
    }

    public function show($id)
    {
        $response = [
            'logisticsType' => $this->logisticsType->get($id),
        ];
        return view('logisticsType.show', $response);
    }

    public function edit($id)
    {
        $response = [
            'logisticsTypes' => $this->logisticsType->getLogistics(),
            'logisticsType' => $this->logisticsType->get($id),
        ];
        return view('logisticsType.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->logisticsType->rules('update', $id));
        $this->logisticsType->update($id, $this->request->all());
        return redirect(route('logisticsType.index'));
    }

    public function destroy($id)
    {
        $this->logisticsType->destroy($id);
        return redirect(route('logisticsType.index'));
    }

}