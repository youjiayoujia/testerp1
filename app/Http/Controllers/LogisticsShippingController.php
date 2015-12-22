<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午6:28
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Repositories\LogisticsShippingRepository;

class LogisticsShippingController extends Controller
{
    protected $logisticsShipping;

    public function __construct(Request $request, LogisticsShippingRepository $logisticsShipping)
    {
        $this->request = $request;
        $this->logisticsShipping = $logisticsShipping;
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'data' => $this->logisticsShipping->auto()->paginate(),
        ];
        return view('logisticsShipping.index', $response);
    }

    public function show($id)
    {
        $response = [
          'logisticsShipping' => $this->logisticsShipping->get($id),
        ];
        return view('logisticsShipping.show', $response);
    }

    public function create()
    {
        $response = [
            'logisticsShippings' => $this->logisticsShipping->getLogistics(),
            'logisticsShipping' => $this->logisticsShipping->getLogisticsType(),
        ];
        return view('logisticsShipping.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->logisticsShipping->rules('create'));
        $this->logisticsShipping->create($this->request->all());
        return redirect(route('logisticsShipping.index'));
    }

    public function edit($id)
    {
        $response = [
            'logisticsShipping' => $this->logisticsShipping->get($id),
            'logisticsShippings' => $this->logisticsShipping->getLogistics(),
            'logisticsShippingss' => $this->logisticsShipping->getLogisticsType(),
        ];
        return view('logisticsShipping.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->logisticsShipping->rules('update', $id));
        $this->logisticsShipping->update($id, $this->request->all());
        return redirect(route('logisticsShipping.index'));
    }

    public function destroy($id)
    {
        $this->logisticsShipping->destroy($id);
        return redirect(route('logisticsShipping.index'));
    }

}