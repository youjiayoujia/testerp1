<?php
/**
 * spu管理控制器
 * @author: youjia
 * Date: 2016-8-2 10:46:32
 */
namespace App\Http\Controllers;

use App\Models\SpuModel;


class SpuController extends Controller
{
    public function __construct(SpuModel $spu)
    {
        $this->model = $spu;
        $this->mainIndex = route('spu.index');
        $this->mainTitle = 'SPU列表';
        $this->viewPath = 'spu.';
    }


}
