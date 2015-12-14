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
            'columns' => $this->logistics->columns,
            'data' => $this->logistics->index($this->request),
        ];
        return view('logistics.index', $response);
    }

    public function create()
    {
        $response = [

        ];
        return view('logistics.create', $response);
    }

    public function store(Request $request)
    {
        $this->request->flash();
        $this->validate($this->request, $this->logistics->rules);
        $this->logistics->store($this->request);
        return redirect(route('logistics.index'));
    }

    public function show($id)
    {
        $response = [
            'logistics' => $this->logistics->detail($id),
        ];
        return view('logistics.show', $response);
    }

    public function edit($id)
    {
        $response = [
            'logistics' => $this->logistics->edit($id),
        ];
        return view('logistics.edit', $response);
    }

    public function update(Request $request, $id)
    {
        $rules = $this->logistics->rules;
        $rules['logistics_name'] .= $id;
        $this->request->flash();
        $this->validate($this->request, $rules);
        $this->logistics->update($id, $this->request);
        return redirect(route('logistics.index'));
    }

    public function destroy($id)
    {
        $this->logistics->destroy($id);
        return redirect(route('logistics.index'));
    }

}