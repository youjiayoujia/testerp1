<?php
/**
 * 黑名单控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/5/6
 * Time: 上午9:23
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\Order\BlacklistModel;
use App\Models\Order\RefundModel;
use App\Models\OrderModel;
use Excel;

class BlacklistController extends Controller
{
    public function __construct(BlacklistModel $blacklist)
    {
        $this->model = $blacklist;
        $this->mainIndex = route('orderBlacklist.index');
        $this->mainTitle = '黑名单';
        $this->viewPath = 'order.blacklist.';
    }

    /**
     * 跳转创建页面
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 跳转编辑页面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'channels' => ChannelModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'channels' => ChannelModel::all(),
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function getBlacklist()
    {
        //根据邮编和收货人相同抓取黑名单用户
        $channel_id = ChannelModel::where('name', 'Wish')->first()->id;
        $orders = OrderModel::where('created_at', '<=', date('Y-m-d H:m:s'))
            ->where('created_at', '>=', date('Y-m-d H:m:s', strtotime("last year")))
            ->where('channel_id', $channel_id)
            ->groupBy('shipping_zipcode', 'shipping_lastname', 'shipping_firstname')
            ->get();
        foreach($orders as $key => $order) {
            if(count($order) >= 5) {
                $count = 0;
                foreach($order as $value) {
                    $refunds = RefundModel::where('order_id', $value['id'])->get();
                    if($refunds) {
                        $count++;
                    }
                }
                if($count >= 5) {
                    $channels = [];
                    foreach($order as $v) {
                        $v->update(['blacklist' => '0']);
                        if(!in_array($v['channel_id'], $channels)) {
                            $channels[] = $v['channel_id'];
                        }
                    }
                    foreach($channels as $channel) {
                        $obj = OrderModel::where('shipping_zipcode', $key)->orderBy('id', 'DESC')->first();
                        $data['channel_id'] = $channel;
                        $data['ordernum'] = $obj->ordernum;
                        $data['name'] = $obj->shipping_lastname . ' ' . $obj->shipping_firstname;
                        $data['email'] = $obj->email;
                        $data['zipcode'] = $obj->shipping_zipcode;
                        $data['type'] = 'SUSPECTED';
                        $data['remark'] = NULL;
                        $data['total_order'] = count($order);
                        $data['refund_order'] = $count;
                        $data['refund_rate'] = round(($count / count($order)) * 100) . '%';
                        $data['color'] = 'orange';
                        $blacklist = BlacklistModel::where('zipcode', $data['zipcode'])
                            ->where('name', $data['name'])
                            ->where('channel_id', $channel)
                            ->count();
                        if($blacklist <= 0) {
                            $this->model->create($data);
                        }
                    }
                }
            }
        }

        //根据邮箱相同抓取黑名单用户
        $channel_id2 = ChannelModel::where('name', 'Wish')->first()->id;
        $orders2 = OrderModel::where('created_at', '<=', date('Y-m-d H:m:s'))
            ->where('created_at', '>=', date('Y-m-d H:m:s', strtotime("last year")))
            ->where('channel_id', '!=', $channel_id2)
            ->groupBy('email')
            ->get();
        foreach($orders2 as $key2 => $order2) {
            if(count($order2) >= 5) {
                $count2 = 0;
                foreach($order2 as $val) {
                    $refund = RefundModel::where('order_id', $val['id'])->get();
                    if($refund) {
                        $count2++;
                    }
                }
                if($count2 >= 5) {
                    $channels2 = [];
                    foreach($order2 as $v2) {
                        $v2->update(['blacklist' => '0']);
                        if(!in_array($v2['channel_id'], $channels2)) {
                            $channels2[] = $v2['channel_id'];
                        }
                    }
                    foreach($channels2 as $channel2) {
                        $obj = OrderModel::where('email', $key2)->orderBy('id', 'DESC')->first();
                        $data['channel_id'] = $channel2;
                        $data['ordernum'] = $obj->ordernum;
                        $data['name'] = $obj->shipping_lastname . ' ' . $obj->shipping_firstname;
                        $data['email'] = $obj->email;
                        $data['zipcode'] = $obj->shipping_zipcode;
                        $data['type'] = 'SUSPECTED';
                        $data['remark'] = NULL;
                        $data['total_order'] = count($order2);
                        $data['refund_order'] = $count2;
                        $data['refund_rate'] = round(($count2 / count($order2)) * 100) . '%';
                        $data['color'] = 'green';
                        $blacklist = BlacklistModel::where('email', $data['email'])->where('channel_id', $channel2)->count();
                        if($blacklist <= 0) {
                            $this->model->create($data);
                        }
                    }
                }
            }
        }

        //周日更新黑名单
        if(date('w') == 0) {
            foreach($this->model->all() as $blacklist) {
                if($blacklist->channel->name == 'Wish') {
                    $lastname = explode(' ', $blacklist['name'])[0];
                    $firstname = explode(' ', $blacklist['name'])[1];
                    $orders = OrderModel::where('shipping_zipcode', $blacklist->zipcode)
                        ->where('shipping_lastname', $lastname)
                        ->where('shipping_firstname', $firstname)
                        ->orderBy('id', 'ASC')
                        ->get();
                }else {
                    $orders = OrderModel::where('email', $blacklist->email)
                        ->orderBy('id', 'ASC')
                        ->get();
                }
                $count3 = 0;
                $ordernum = '';
                foreach($orders as $order) {
                    if(count($order->refunds)) {
                        $count3++;
                    }
                    $ordernum = $order['ordernum'];
                }
                $data['ordernum'] = $ordernum;
                $data['refund_order'] = $count3;
                $data['total_order'] = count($orders);
                $data['refund_rate'] = round(($count3 / count($orders)) * 100) . '%';
                $blacklist->update($data);
            }
        }

    }

    /**
     * 批量审核
     *
     * @return int
     */
    public function listAll()
    {
        $blacklist_status = request()->input('blacklist_status');
        $blacklist_ids = request()->input('blacklist_ids');
        $blacklist_id_arr = explode(',', $blacklist_ids);
        foreach($blacklist_id_arr as $id) {
            $model = $this->model->find($id);
            $data['type'] = $blacklist_status;
            $model->update($data);
        }
        return 1;
    }

    /**
     * 导出所有内单号
     */
    public function exportAll()
    {
        $rows = $this->model->exportAll();
        $this->exportExcel($rows, 'export_all_blacklists', '导出所有内单号');
    }

    /**
     * 导出勾选内单号
     */
    public function exportPart()
    {
        $blacklist_ids = request()->input('blacklist_ids');
        $blacklist_id_arr = explode(',', $blacklist_ids);
        $rows = $this->model->exportPart($blacklist_id_arr);
        $this->exportExcel($rows, 'export_part_blacklists', '导出勾选内单号');
    }

    public function exportExcel($rows, $name)
    {
        Excel::create($name, function($excel) use ($rows){
            $excel->sheet('', function($sheet) use ($rows){
                $sheet->fromArray($rows);
            });
        })->download('csv');
    }

    public function uploadBlacklist()
    {
        if(request()->hasFile('excel'))
        {
            $file = request()->file('excel');
            $function = 'excelBlacklistProcess';
            $errors = $this->model->excelProcess($file, $function);
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];

            return view($this->viewPath.'uploadBlacklistResult', $response);
        }
    }

    public function downloadUpdateBlacklist()
    {
        $rows = [
            [
                'channel_id' => '1',
                'ordernum' => '17905581340',
                'name' => 'ThaiDiane',
                'email' => 'hannawysz@gmail.com',
                'zipcode' => '210000',
                'type' => 'SUSPECTED',
                'remark' => '',
                'refund_order' => '1',
                'total_order' => '10',
                'refund_rate' => '10%',
            ]
        ];
        $name = 'update_blacklist';
        $this->exportExcel($rows, '更新黑名单用户');
    }

}