<?php

namespace App\Http\Controllers;

use App\Repositories\CarRepository;
use Illuminate\Http\Request;
use App\Helps;

class DashboardController extends Controller
{

    protected $car;
    protected $listColumns;

    public function __construct(CarRepository $car)
    {
        $this->car = $car;
    }

    public function index()
    {
        $datas = [
            'columns' => $this->car->jGridColumns(),
        ];
        return view('common.jgrid', $datas);
    }

    public function test(Request $request)
    {
        echo Helps::paginateToJGrid($this->car->index($request));
    }

}
