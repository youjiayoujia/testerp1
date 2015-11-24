<?php

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 15/11/17
 * Time: 下午5:02
 */

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;


class ProductController extends Controller
{
    protected $product;

    public function __construct(Request $request, ProductRepository $product)
    {
        $this->request = $request;
        $this->product = $product;
    }

    public function index()
    {
        $this->request->flash();
        $response = [
            'columns' => $this->product->columns(),
            'data' => $this->product->index($this->request),
        ];
        return view('product.index', $response);
    }

    public function create()
    {
        $response = [
            'brands' => $this->product->getAllBrands()
        ];
        return view('product.create', $response);
    }

    public function store()
    {
        $this->request->flash();
        $this->validate($this->request, $this->product->rules);
        $this->product->store($this->request);

        return redirect(route('product.index'));
    }
}