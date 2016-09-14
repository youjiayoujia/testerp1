<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/9/14
 * Time: 17:46
 */
namespace App\Models\Message\Issues;
use App\Base\BaseModel;
class AliexpressIssuesDetailModel extends BaseModel
{
    protected $table = 'aliexpress_issues_detail';
    public $rules = [];
    public $searchFields =[];
    protected $guarded = [];


}