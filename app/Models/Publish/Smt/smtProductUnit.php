<?php

namespace App\models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class smtProductUnit extends Model
{
    protected $table = "smt_product_unit";
    
    public function getAllUnit(){
        $rs = array();
        $result = $this->all()->toArray();    
        if ($result) {
            foreach ($result as $row) {
                $rs[$row['id']] = $row;
            }
        }
        return $rs;
       
    }
}
