<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:42
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

    public function create()
    {
        return view('logistics.create');
    }

    public function store()
    {
        $this->request->flash();
//        $rules = [
//            'name' => 'required|unique:logistics,name',
//            'customer_id' => 'required',
//        ];
        $this->validate($this->request, $this->logistics->rules('create'));
//        $data = [];
//        $data['name'] = $this->request->input('name');
//        $data['customer_id'] = $this->request->input('customer_id');
//        $data['secret_key'] = $this->request->input('secret_key');
//        $data['is_api'] = $this->request->input('is_api');
//        $data['client_manager'] = $this->request->input('client_manager');
//        $data['manager_tel'] = $this->request->input('manager_tel');
//        $data['technician'] = $this->request->input('technician');
//        $data['technician_tel'] = $this->request->input('technician_tel');
//        $data['remark'] = $this->request->input('remark');
//        $this->logistics->store($data);
        $this->logistics->create($this->request->all());
        return redirect(route('logistics.index'));
    }

    public function show($id)
    {
        $response = [
            'logistics' => $this->logistics->get($id),
        ];
        return view('logistics.show', $response);
    }

    public function edit($id)
    {
        $response = [
            'logistics' => $this->logistics->get($id),
        ];
        return view('logistics.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->logistics->rules('update', $id));
        $this->logistics->update($id, $this->request->all());
//        $data = [];
//        $data['name'] = $this->request->input('name');
//        $data['customer_id'] = $this->request->input('customer_id');
//        $data['secret_key'] = $this->request->input('secret_key');
//        $data['is_api'] = $this->request->input('is_api');
//        $data['client_manager'] = $this->request->input('client_manager');
//        $data['manager_tel'] = $this->request->input('manager_tel');
//        $data['technician'] = $this->request->input('technician');
//        $data['technician_tel'] = $this->request->input('technician_tel');
//        $data['remark'] = $this->request->input('remark');
//        $this->logistics->update($id, $data);
        return redirect(route('logistics.index'));
    }

    public function destroy($id)
    {
        $this->logistics->destroy($id);
        return redirect(route('logistics.index'));
    }

}