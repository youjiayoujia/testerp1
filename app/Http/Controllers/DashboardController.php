<?php

namespace App\Http\Controllers;

use App\Repositories\CarRepository;
use Illuminate\Http\Request;
use App\Helps;

class DashboardController extends Controller
{

    protected $car;

    public function __construct(CarRepository $car)
    {
        $this->car = $car;
    }

    public function index(Request $request)
    {
        $request->flash();
        $data = [
            'columns' => $this->car->columns(),
            'brands' => $this->car->getAllBrands(),
            'datas' => $this->car->index($request),
        ];
        return view('dashboard.test', $data);
    }

//    public function test(Request $request)
//    {
//        echo Helps::paginateToGrid($this->car->index($request));
//    }

}
