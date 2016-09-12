<?php

namespace App\Models\Product;

use App\Base\BaseModel;

class RequireModel extends BaseModel
{
	protected $table = 'product_requires';

	protected $fillable = [
            'img1', 'img2', 'img3', 'img4', 'img5', 'img6', 'color', 'url1', 'url2', 'url3', 'material', 'technique', 'parts',
            'name', 'province', 'city', 'similar_sku', 'competition_url', 
            'remark', 'expected_date', 'needer_id', 'needer_shop_id', 
            'created_by', 'status', 'handle_id', 'handle_time', 'catalog_id','purchase_id'
            ];

    // 规则验证
    public $rules = [
        'create' => [   
                'name' => 'required|max:255|unique:product_requires,name',
                //'needer_id' => 'required',
                //'needer_shop_id' => 'required'
        ],
        'update' => [   
                'name' => 'required|max:255|unique:product_requires,name, {id}',
                //'needer_id' => 'required',
                //'needer_shop_id' => 'required',
        ]
    ];

    //查询
    public $searchFields = ['name'=>'名称'];
    
    /**
     *  移动文件 
     *
     *  @param $fd 类文件指针
     *  @param $name 文件名 
     *  @param $path 路径
     *
     *  @return path
     */
    public function move_file($fd, $name, $path)
    {
        $dstname = $name.'.'.$fd->getClientOriginalExtension();
        if(file_exists($path.'/'.$dstname))
            unlink($path.'/'.$dstname);
        $fd->move($path,$dstname);

        return "/".$path."/".$dstname;
    }

    /**
     * return the relationship  
     *
     * @return relation
     *
     */
    public function createdByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'created_by', 'id');
    }

    /**
     * return the relationship  
     *
     * @return relation
     *
     */
    public function userName()
    {
        return $this->belongsTo('App\Models\UserModel', 'handle_id', 'id');
    }

    /**
     * return the relationship  
     *
     * @return relation
     *
     */
    public function neederName()
    {
        return $this->belongsTo('App\Models\UserModel', 'needer_id', 'id');
    }

    /**
     * return the relationship  
     *
     * @return relation
     *
     */
    public function catalogByName()
    {
        return $this->belongsTo('App\Models\CatalogModel', 'catalog_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'needer_id', 'id');
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'needer_shop_id', 'id');
    }
}