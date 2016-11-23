<?php
/**
 * 收款信息控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/8/5
 * Time: 下午4:54
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\CollectionInfoModel;

class CollectionInfoController extends Controller
{
    public function __construct(CollectionInfoModel $collectionInfo)
    {
        $this->model = $collectionInfo;
        $this->mainIndex = route('logisticsCollectionInfo.index');
        $this->mainTitle = '收款信息';
        $this->viewPath = 'logistics.collectionInfo.';
    }
}