<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/7/12
 * Time: 下午2:28
 */
namespace App\Models\Log\Command;

use App\Base\BaseModel;

class ItemModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_command_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $searchFields = [];
}

