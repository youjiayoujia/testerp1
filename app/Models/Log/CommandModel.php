<?php

namespace App\Models\Log;

use App\Base\BaseModel;

class CommandModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_commands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    //查询
    public $searchFields = ['relation_id', 'signature', 'description', 'remark'];

    public function getColorAttribute()
    {
        if ($this->result == 'fail') {
            return 'danger';
        }
        if ($this->result == 'init') {
            return 'warning';
        }
        if ($this->result == 'success') {
            return 'success';
        }
    }

}
