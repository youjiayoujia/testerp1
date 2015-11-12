<?php

namespace App\Http\Controllers;

use Config;

class MainController extends Controller
{

    public function index()
    {
        $navigations = Config::get('navigation');
        $data = array(
            'navigations' => $navigations,
        );
        return view('main.index', $data);
    }

}
