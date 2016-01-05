<?php
/**
 * 跟踪号控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/28
 * Time: 上午10:50
 */

namespace App\Http\Controllers\Logistics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Logistics\CodeRepository;
use App\Repositories\LogisticsRepository;

class CodeController extends Controller
{
    protected $code;

    public function __construct(Request $request, CodeRepository $code)
    {
        $this->request = $request;
        $this->code = $code;
        $this->mainIndex = route('logisticsCode.index');
        $this->mainTitle = '跟踪号';
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->code->auto()->paginate(),
        ];
        return view('logistics.code.index', $response);
    }

    public function create(LogisticsRepository $logistics)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logistics' => $logistics->all(),
        ];
        return view('logistics.code.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->code->rules('create'));
        $this->code->create($this->request->all());
        return redirect($this->mainIndex);
    }

    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'code' => $this->code->get($id),
        ];
        return view('logistics.code.show', $response);
    }

    public function edit($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'code' => $this->code->get($id),
        ];
        return view('logistics.code.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->code->rules('update', $id));
        $this->code->update($id, $this->request->all());
        return redirect($this->mainIndex);
    }

    public function destroy($id)
    {
        $this->code->destroy($id);
        return redirect($this->mainIndex);
    }

}