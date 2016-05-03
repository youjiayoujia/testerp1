<?php
/**
 * 国家信息控制器
 * 国家信息相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountriesModel;

class CountriesController extends Controller
{
    public function __construct(CountriesModel $countries)
    {
        $this->model = $countries;
        $this->mainIndex = route('countries.index');
        $this->mainTitle = '国家信息';
        $this->viewPath = 'countries.';
    }
}