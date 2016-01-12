<?php
/**
 * 物流分区报价(小包)控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/11
 * Time: 下午4:20
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Logistics\ZonePricePacketRepository;

class ZonePricePacketController extends Controller
{
    protected $zonePricePacket;

    public function __construct(Request $request, ZonePricePacketRepository $zonePricePacket)
    {
        $this->request = $request;
        $this->zonePricePacket = $zonePricePacket;
        $this->mainIndex = route('logisticsZonePricePacket.index');
        $this->mainTitle = '物流分区报价(小包)';
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->zonePricePacket->auto()->paginate(),
        ];
        return view('logistics.zone.price.packet.index', $response);
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view('logistics.zone.price.packet.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->zonePricePacket->rules('create'));
        $this->zonePricePacket->create($this->request->all());
        return redirect($this->mainIndex);
    }

    public function show($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zonePricePacket' => $this->zonePricePacket->get($id),
        ];
        return view('logistics.zone.price.packet.show', $response);
    }

    public function edit($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'zonePricePacket' => $this->zonePricePacket->get($id),
        ];
        return view('logistics.zone.price.packet.edit', $response);
    }

    public function update($id)
    {
        $this->request->flash();
        $this->validate($this->request, $this->zonePricePacket->rules('update', $id));
        $this->zonePricePacket->update($id, $this->request->all());
        return redirect($this->mainIndex);
    }

    public function destroy($id)
    {
        $this->zonePricePacket->destroy($id);
        return redirect($this->mainIndex);
    }

}