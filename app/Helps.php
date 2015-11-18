<?php

namespace App;

class Helps
{

    public static function toGrid($paginate)
    {
        $paginateArray = $paginate->toArray();
        $gridArray = [
            "total" => $paginateArray['total'],
            "pageSize" => $paginateArray['per_page'],
            "pageCurrent" => $paginateArray['current_page'],
            "rows" => $paginateArray['data']
        ];

        return json_encode($gridArray);
    }

}
