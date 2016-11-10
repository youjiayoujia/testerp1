<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $rules;

    public function rules($type, $id = '')
    {
        $rules = $this->rules[$type];
        if ($id) {
            foreach ($rules as $column => $rule) {
                $rules[$column] = str_replace('{id}', $id, $rule);
            }
        }
        return $rules;
    }

    public function showSearch()
    {
        $searchFields = $this->searchFields;
        $str = '';
        if ($searchFields) {
            foreach ($searchFields as $key => $searchField) {
                $str .= $searchField . ',';
            }
        }

        return substr($str, 0, strlen($str) - 1);
    }

    public function getArray($model, $name)
    {
        $arr = [];
        $inner_models = $model::all();
        foreach ($inner_models as $key => $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }
}
