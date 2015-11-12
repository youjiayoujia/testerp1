<?php

namespace App\Http\Controllers;

use App\Repositories\CarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        return view('common.jgrid');
    }

    public function test(Request $request)
    {
        echo Helps::paginateToJGrid($this->car->index($request));
    }

}
