<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/9/14
 * Time: 15:31
 */
namespace App\Models\Message\Issues;
use App\Base\BaseModel;
use App\Models\Channel\AccountModel;

class AliexpressIssueListModel extends BaseModel
{
    protected $table = 'aliexpress_issues_list';
    public $rules = [];
    public $searchFields =[];
    protected $guarded = [];

    public function account()
    {
        return $this->hasOne('App\Models\Channel\AccountModel','id','account_id');

    }

    public function detail()
    {
        return $this->hasOne('App\Models\Message\Issues\AliexpressIssuesDetailModel', 'id', 'issue_list_id');
    }

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
                'aliexpress_issues_list.orderId',
            ],
           'filterSelects' => [
               'issueType' => config('message.aliexpress.issueType'),
                'reasonChinese' => $this->distinct()->get(['reasonChinese'])->pluck('reasonChinese', 'reasonChinese'),
            ],
           'selectRelatedSearchs' => [
               'account' => ['account' => AccountModel::all()->pluck('alias', 'account')],
            ],
            //'sectionSelect' => ['time'=>['created_at']],
        ];
    }

    public function getIssueTypeNameAttribute(){
        if($this->issueType){
            return config('message.aliexpress.issueType')[$this->issueType];
        }else{
            return '';
        }
    }
    public function getaccountNameAttribute(){
        if($account = $this->account){
            return  $account->account ? $account->account : '';
        }else{
            return '';
        }
    }

}
