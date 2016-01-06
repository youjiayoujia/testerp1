<?php
/**
 * 物流分区控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/6
 * Time: 上午11:46
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Repositories\Logistics\ZoneRepository;
use App\Repositories\LogisticsRepository;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    protected $zone;

    public function __construct(Request $request, ZoneRepository $zone)
    {
        $this->request = $request;
        $this->zone = $zone;
        $this->mainIndex = route('logisticsZone.index');
        $this->mainTitle = '物流分区';
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->zone->auto()->paginate(),
        ];
        return view('logistics.zone.index', $response);
    }

    public function create(LogisticsRepository $logistics)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'logistics' => $logistics->all(),
        ];
        return view('logistics.zone.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->zone->rules('create'));
        $this->zone->create($this->request->all());
        return redirect($this->mainIndex);
    }

    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $this->zone->get($id),
        ];
        return view('logistics.zone.show', $response);
    }

    public function edit($id, LogisticsRepository $logistics)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zone' => $this->zone->get($id),
            'logistics' => $logistics->all(),
        ];
        return view('logistics.zone.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->zone->rules('update', $id));
        $this->zone->update($id, $this->request->all());
        return redirect($this->mainIndex);
    }

    public function destroy($id)
    {
        $this->zone->destroy($id);
        return redirect($this->mainIndex);
    }
}