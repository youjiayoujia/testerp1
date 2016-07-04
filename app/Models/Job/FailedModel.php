<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Model;

class FailedModel extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'failed_jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    //查询
    public $searchFields = ['queue'];

}
