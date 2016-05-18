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
use App\Models\Order\BlacklistModel;
use App\Models\OrderModel;

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
     * 更新黑名单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $orders = OrderModel::where(['status' => 'CANCEL'])->get()->toArray();
        foreach($orders as $order) {
            $count = OrderModel::where(['email' => $order['email'], 'status' => 'CANCEL'])->count();
            if($count >= 5) {
                $data['name'] = $order['shipping_lastname'] . $order['shipping_firstname'];
                $data['email'] = $order['email'];
                $data['zipcode'] = $order['shipping_zipcode'];
                $data['whitelist'] = '0';
                $count1 = BlacklistModel::where(['name' => $data['name']])->count();
                if($count1 == 0) {
                    $this->model->create($data);
                    $id = BlacklistModel::where(['name' => $data['name']])->first()->id;
                    $order->update(['blacklist' => '0', 'status' => 'NEW',
                        'import_remark' => '邮箱'.$data['email'].'/收货人邮编'.$data['zipcode'].'+收货人姓名'.$data['name'].'存在黑名单中,id为'.$id]);
                }
            }
        }

//        $emails = OrderModel::distinct()->get(['email'])->toArray();
//        foreach($emails as $email) {
//            $orders = OrderModel::where(['email' => $email, 'status' => 'CANCEL'])->get();
//            if($orders->count() >= 5) {
//                foreach($orders as $order) {
//                    $data['name'] = $order['shipping_lastname'] . $order['shipping_firstname'];
//                    $data['email'] = $email['email'];
//                    $data['zipcode'] = $order['shipping_zipcode'];
//                    $data['whitelist'] = '0';
//                    $count = BlacklistModel::where(['name' => $data['name']])->count();
//                    $id = BlacklistModel::where(['name' => $data['name']])->first()->id;
//                    $order->update(['blacklist' => '0', 'status' => 'NEW',
//                        'import_remark' => '邮箱'.$data['email'].'/收货人邮编'.$data['zipcode'].'+收货人姓名'.$data['name'].'存在黑名单中,id为'.$id]);
//                    if($count == 0) {
//                        $this->model->create($data);
//                    }
//                }
//            }
//        }
//        $zipcodes = OrderModel::distinct()->get(['shipping_zipcode'])->toArray();
//        foreach($zipcodes as $zipcode) {
//            $firstnames = OrderModel::distinct()->get(['shipping_firstname'])->toArray();
//            foreach($firstnames as $firstname) {
//                $lastnames = OrderModel::distinct()->get(['shipping_lastname'])->toArray();
//                foreach($lastnames as $lastname) {
//                    $orders = OrderModel::where(['shipping_zipcode' => $zipcode,
//                        'shipping_firstname' => $firstname, 'shipping_lastname' => $lastname,
//                        'status' => 'CANCEL'])->get();
//                    if($orders->count() >= 5) {
//                        foreach($orders as $order) {
//                            $data['name'] = $order['shipping_lastname'] . $order['shipping_firstname'];
//                            $data['email'] = $order['email'];
//                            $data['zipcode'] = $order['shipping_zipcode'];
//                            $data['whitelist'] = '0';
//                            $count = BlacklistModel::where(['name' => $data['name']])->count();
//                            if($count == 0) {
//                                $this->model->create($data);
//                                $id = BlacklistModel::where(['name' => $data['name']])->first()->id;
//                                $order->update(['blacklist' => '0', 'status' => 'NEW',
//                                    'import_remark' => '邮箱'.$data['email'].'/收货人邮编'.$data['zipcode'].'+收货人姓名'.$data['name'].'存在黑名单中,id为'.$id]);
//                            }
//                        }
//                    }
//                }
//            }
//        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);
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
            $data['whitelist'] = $blacklist_status;
            $model->update($data);
        }
        return 1;
    }

}