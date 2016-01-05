<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    protected $request;
    protected $mainIndex;
    protected $mainTitle;

    public function metas($action, $title = null)
    {
        $metas = [
            'mainIndex' => $this->mainIndex,
            'mainTitle' => $this->mainTitle,
            'title' => $title ? $title : $this->mainTitle . config('setting.titles.' . $action),
        ];

        return $metas;
    }
}
