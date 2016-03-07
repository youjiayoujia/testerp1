<?php
/**
 *  供货商控制器
 *  处理与供货商相关的操作
 *
 * @author:MC<178069409@qq.com>
 *    Date:2015/12/18
 *    Time:11:18
 *
 */

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\Product\SupplierModel;

class SupplierController extends Controller
{
    public function __construct(SupplierModel $supplier)
    {
        $this->model = $supplier;
        $this->mainIndex = route('productSupplier.index');
        $this->mainTitle = '供货商';
        $this->viewPath = 'product.supplier.';
    }

    // public function update($id)
    // {
    //     request()->flash();
    //     $this->validate(request(), $this->model->rules('update', $id));

    //     var_dump(request()->all());
    //     exit;
    // }
}