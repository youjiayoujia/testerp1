<?php
/**
 * 物流分区报价(快递)控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/11
 * Time: 下午4:19
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Logistics\ZonePriceExpressRepository;
use App\Repositories\LogisticsRepository;

class ZonePriceExpressController extends Controller
{
    protected $zonePriceExpress;

    public function __construct(Request $request, ZonePriceExpressRepository $zonePriceExpress)
    {
        $this->request = $request;
        $this->zonePriceExpress = $zonePriceExpress;
        $this->mainIndex = route('logisticsZonePriceExpress.index');
        $this->mainTitle = '物流分区报价(快递)';
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->zonePriceExpress->auto()->paginate(),
        ];
        return view('logistics.zone.price.express.index', $response);
    }

    public function create(LogisticsRepository $logistics)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logistics' => $logistics->all(),
        ];
        return view('logistics.zone.price.express.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->zonePriceExpress->rules('create'));
        $this->zonePriceExpress->create($this->request->all());
        return redirect($this->mainIndex);
    }

    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zonePriceExpress' => $this->zonePriceExpress->get($id),
        ];
        return view('logistics.zone.price.express.show', $response);
    }

    public function edit($id, LogisticsRepository $logistics)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zonePriceExpress' => $this->zonePriceExpress->get($id),
            'logistics' => $logistics->all(),
        ];
        return view('logistics.zone.price.express.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->zonePriceExpress->rules('update', $id));
        $this->zonePriceExpress->update($id, $this->request->all());
        return redirect($this->mainIndex);
    }

    public function destroy($id)
    {
        $this->zonePriceExpress->destroy($id);
        return redirect($this->mainIndex);
    }

}