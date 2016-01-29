<?php

namespace App\Models;

use App\Base\BaseModel;

class ItemModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'items';

	protected $searchFields = ['sku'];

    public $rules = [
        'create' => ['sku' => 'required|unique:items,sku'],
        'update' => ['sku' => 'required|unique:items,sku,{id}']
    ];

	protected $fillable = [
        'product_id','sku','weight'
        ];
}