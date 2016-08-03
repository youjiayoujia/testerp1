<?php
/**
 *
 * Paypal控制器
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-05-31
 * Time: 13:46
 */
namespace App\Http\Controllers;
use App\Models\PaypalsModel;
use App\Models\PermissionModel;

class PaypalController extends Controller
{
    public function __construct(PaypalsModel $paypal)
    {
        $this->model = $paypal;
        $this->mainIndex = route('paypal.index');
        $this->mainTitle = 'paypal';
        $this->viewPath = 'paypal.';
    }
    public function ShowPaypalRate(){
        $fee_array = [
            '固定费'=>config('paypal.fixed_fee'),
            'PP大成交费'=>config('paypal.transactions_fee_big'),
            'PP小成交费'=>config('paypal.transactions_fee_small'),
        ];
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'fee_array'=>$fee_array,
        ];


        return view('paypal.paypal_rate',$response);
    }

}