<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Event\CategoryModel;

class BaseModel extends Model
{
    // use SoftDeletes;
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

    public function eventLog($user, $content = '', $to = '', $from = '')
    {
        $modelName = $this->table;
        if($modelName) {
            $category = CategoryModel::where('model_name', $modelName)->first();
            if(!$category) {
                $category = CategoryModel::create(['model_name' => $modelName]);
            }
            $category->child()->create(['type_id' => ($to ? json_decode($to)->id : $this->id), 'what' => $content, 'when' => date('Y-m-d H:i:s', time()), 'to_arr' => $to, 'from_arr' => $from, 'who' => $user]);
        }
    }
    
    public function getAvailableWarehouse($model, $name)
    {
        $arr = [];
        $inner_models = $model::where('is_available','1')->get();
        foreach ($inner_models as $key => $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }
}
