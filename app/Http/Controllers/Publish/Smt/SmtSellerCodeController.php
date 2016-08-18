<?php

namespace App\Http\Controllers\Publish\Smt;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtUserSaleCode;

class SmtSellerCodeController extends Controller
{
    public function __construct(smtUserSaleCode $sellerCode)
    {
        $this->model = $sellerCode;
        $this->mainIndex = route('smtSellerCode.index');
        $this->mainTitle = 'smt销售代码';
        $this->viewPath = 'publish.smt.smtSellerCode.';
    }


    public function create()
    {
        //
    }


    public function edit($id)
    {
        //
    }


}
