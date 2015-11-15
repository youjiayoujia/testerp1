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

    public function index()
    {
        $data = [
            'columns' => $this->car->gridColumns(),
        ];
        return view('common.grid', $data);
    }

    public function test(Request $request)
    {
        echo Helps::paginateToGrid($this->car->index($request));
    }

}
