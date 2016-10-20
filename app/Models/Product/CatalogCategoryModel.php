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
    public $table = 'catalog_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['id','cn_name','en_name'];

    protected $rules = [
        'create' => [
            'cn_name' => 'required|unique:catalog_category,cn_name',
            'en_name' => 'required|unique:catalog_category,en_name',
        ],
        'update' => [
            'cn_name' => 'required|unique:catalog_category,cn_name',
            'en_name' => 'required|unique:catalog_category,en_name',
        ]
    ];
    protected $searchFields = [];

}