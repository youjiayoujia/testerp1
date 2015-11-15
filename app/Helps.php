<?php

namespace App;

class Helps
{

    public static function paginateToJGrid($paginate)
    {
        $paginateArray = $paginate->toArray();
        $jGridArray = [
            "selectPageSize" => '20,50,100',
            "total" => $paginateArray['total'],
            "pageSize" => $paginateArray['per_page'],
            "pageCurrent" => $paginateArray['current_page'],
            "list" => $paginateArray['data']
        ];

        return json_encode($jGridArray);
    }

}
