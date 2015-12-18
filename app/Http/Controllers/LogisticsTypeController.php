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
            'data' => $this->logisticsType->paginate(),
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
        $rules = [
            'type' => 'required|unique:logistics_type,type',
            'logistics_id' => 'required',
        ];
        $this->validate($this->request, $rules);
        $data = [];
        $data['type'] = $this->request->input('type');
        $data['logistics_id'] = $this->request->input('logistics_id');
        $data['remark'] = $this->request->input('remark');
        $this->logisticsType->store($data);
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
        $data = [];
        $data['type'] = $this->request->input('type');
        $data['logistics_id'] = $this->request->input('logistics_id');
        $data['remark'] = $this->request->input('remark');
        $this->logisticsType->update($id, $data);
        return redirect(route('logisticsType.index'));
    }

    public function destroy($id)
    {
        $this->logisticsType->destroy($id);
        return redirect(route('logisticsType.index'));
    }

}