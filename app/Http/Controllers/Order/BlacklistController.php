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
     * 批量审核
     *
     * @return int
     */
    public function listAll()
    {
        $examine_status = request()->input('examine_status');
        $product_ids = request()->input('product_ids');
        $product_id_arr = explode(',', $product_ids);
        var_dump($product_id_arr);exit;
        foreach ($product_id_arr as $id) {
            $model = $this->model->find($id);
            $data['whitelist'] = $examine_status;
            $model->update($data);
        }
        return 1;
    }

}