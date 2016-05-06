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