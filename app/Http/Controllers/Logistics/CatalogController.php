<?php
/**
 * 物流分类控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/11
 * Time: 下午2:12
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\CatalogModel;

class CatalogController extends Controller
{
    public function __construct(CatalogModel $catalog)
    {
        $this->model = $catalog;
        $this->mainIndex = route('logisticsCatalog.index');
        $this->mainTitle = '物流分类';
        $this->viewPath = 'logistics.catalog.';
    }

}