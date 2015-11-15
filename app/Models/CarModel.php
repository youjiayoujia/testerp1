<?php

namespace App\Models;

use App\Base\BaseModel;

class CarModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'color'];

    /**
     * grid列表显示字段
     *
     * @var array
     */
    public $gridColumns = [
        ['name' => 'size', 'label' => '型号'],
        ['name' => 'color', 'label' => '颜色'],
        ['name' => 'created_at', 'label' => '创建日期', 'type' => 'date']
    ];

    /**
     * jGrid列表过滤字段
     *
     * @var array
     */
    public $filters = ['size', 'color', 'created_at'];

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

}
