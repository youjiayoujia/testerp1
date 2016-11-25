<?php
/**
 * 国家转换控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/11/25
 * Time: 下午1:48
 */

namespace App\Http\Controllers;

use App\Models\CountriesChangeModel;

class CountriesChangeController extends Controller
{
    public function __construct(CountriesChangeModel $countriesChange)
    {
        $this->model = $countriesChange;
        $this->mainIndex = route('countriesChange.index');
        $this->mainTitle = '国家转换';
        $this->viewPath = 'countries.change.';
    }
}