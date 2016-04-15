<?php
/**
 * 仓库控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyModel;

class CurrencyController extends Controller
{
    public function __construct(CurrencyModel $currency)
    {
        $this->model = $currency;
        $this->mainIndex = route('currency.index');
        $this->mainTitle = '汇率';
        $this->viewPath = 'currency.';
    }
}