<?php

namespace App;

class Helps
{

    public static function paginateToJGrid($paginate)
    {
        $paginateArray = $paginate->toArray();
        $jGridArray = [
            "total" => $paginateArray['total'],
            "pageSize" => $paginateArray['per_page'],
            "pageCurrent" => $paginateArray['current_page'],
            "list" => $paginateArray['data']
        ];

        return json_encode($jGridArray);
    }

}
