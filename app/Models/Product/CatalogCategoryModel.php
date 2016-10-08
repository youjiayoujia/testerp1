<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/9/30
 * Time: 11:08
 */
namespace App\Models\product;
use App\Base\BaseModel;
use Tool;
class CatalogCategoryModel extends BaseModel{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'catalog_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','cn_name','en_name'];

    protected $rules = [
        'create' => [
            'cn_name' => 'required',
            'en_name' => 'required',
        ],
        'update' => [
            'cn_name' => 'required',
            'en_name' => 'required',
        ]
    ];
    protected $searchFields = [];

}