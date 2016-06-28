<?php
/**
 * Created by PhpStorm.
 * User: jiangdi
 * Date: 2016/6/28
 * Time: 9:47
 */
namespace App\Models\Channel;
use App\Base\BaseModel;
class ChannelsModel extends BaseModel{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channels';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}