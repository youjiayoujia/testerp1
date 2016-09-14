<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/9/14
 * Time: 15:31
 */
namespace App\Models\Message\Issues;
use App\Base\BaseModel;

class AliexpressIssueListModel extends BaseModel
{
    protected $table = 'aliexpress_issues_list';
    public $rules = [];
    public $searchFields =[];
    protected $guarded = [];


}
