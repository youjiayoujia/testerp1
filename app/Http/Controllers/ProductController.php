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
use App\Helps;

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
        return view('product.index', $response);
    }

    public function grid(Request $request)
    {
        echo Helps::toGrid($this->product->index($request));
    }

    public function create()
    {
        $response = [
            'brands' => $this->product->getAllBrands()
        ];
        return view('product.create', $response);
    }

    public function store(Request $request)
    {
//        var_dump($request->all());
    }
}