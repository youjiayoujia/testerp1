<?php

namespace App;

class Helps
{

    public static function paginateToGrid($paginate)
    {
        $paginateArray = $paginate->toArray();
        $gridArray = [
            "selectPageSize" => '20,50,100',
            "total" => $paginateArray['total'],
            "pageSize" => $paginateArray['per_page'],
            "pageCurrent" => $paginateArray['current_page'],
            "list" => $paginateArray['data']
        ];

        return json_encode($gridArray);
    }

}
