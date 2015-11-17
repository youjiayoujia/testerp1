<?php

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 15/11/17
 * Time: 下午5:02
 */

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $car;

    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $request->flash();
        $response = [
            'columns' => $this->product->columns(),
            'data' => $this->product->index($request),
        ];
        return view('common.grid');
//        return view('product.index', $response);
    }
}