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
class PaypalController extends Controller
{
    public function __construct(PaypalsModel $paypal)
    {
        $this->model = $paypal;
        $this->mainIndex = route('paypal.index');
        $this->mainTitle = 'paypal';
        $this->viewPath = 'paypal.';
    }

}