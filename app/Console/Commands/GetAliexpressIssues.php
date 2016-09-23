<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Channel\AccountModel;

use App\Models\Message\Issues\AliexpressIssueListModel;
use App\Models\Message\Issues\AliexpressIssuesDetailModel;
use Channel;

class GetAliexpressIssues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issues:get  {accountName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $account_name =  $this->argument('accountName');  //渠道名称

        $account = AccountModel::where('account',$account_name)->first();
        if(is_object($account)){
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $getIssueLists = $channel->getIssues();
            if(!empty($getIssueLists)){
                foreach($getIssueLists as $issue){
                    $issue_list = AliexpressIssueListModel::firstOrNew(['issue_id' => $issue['issue_id']]);
                    if(empty($issue_list->id)){
                        $issue_list->issue_id      = $issue['issue_id'];
                        $issue_list->account_id    = $account->id;
                        $issue_list->gmtModified   = $issue['gmtModified'];
                        $issue_list->issueStatus   = $issue['issueStatus'];
                        $issue_list->gmtCreate     = $issue['gmtCreate'];
                        $issue_list->reasonChinese = $issue['reasonChinese'];
                        $issue_list->orderId       = $issue['orderId'];
                        $issue_list->reasonEnglish = $issue['reasonEnglish'];
                        $issue_list->issueType     = $issue['issueType'];
                        $issue_list->save();

                        $this->info('issue #' .$issue['issue_id']. ' Received.');

                        if(!empty($issue['issue_detail'])){
                            $issue_detail = AliexpressIssuesDetailModel::firstOrNew(['issue_list_id' => $issue_list->id]);
                            if(empty($issue_detail->id)){
                                $issue_detail->issue_list_id = $issue_list->id;
                                $issue_detail->resultMemo    = $issue['issue_detail']->resultMemo;
                                $issue_detail->orderId       = $issue['issue_detail']->resultObject->orderId;
                                $issue_detail->gmtCreate     = $issue['issue_detail']->resultObject->gmtCreate;
                                $issue_detail->issueReasonId = $issue['issue_detail']->resultObject->issueReasonId;
                                $issue_detail->buyerAliid    = $issue['issue_detail']->resultObject->buyerAliid;
                                $issue_detail->issueStatus   = $issue['issue_detail']->resultObject->issueStatus;
                                $issue_detail->issueReason   = $issue['issue_detail']->resultObject->issueReason;
                                $issue_detail->productName   = $issue['issue_detail']->resultObject->productName;

                                //序列化对象
                                $issue_detail->productPrice         = base64_encode(serialize($issue['issue_detail']->resultObject->productPrice));
                                $issue_detail->buyerSolutionList    = base64_encode(serialize($issue['issue_detail']->resultObject->buyerSolutionList));
                                $issue_detail->sellerSolutionList   = base64_encode(serialize($issue['issue_detail']->resultObject->sellerSolutionList));
                                $issue_detail->platformSolutionList = base64_encode(serialize($issue['issue_detail']->resultObject->platformSolutionList));
                                $issue_detail->refundMoneyMax       = base64_encode(serialize($issue['issue_detail']->resultObject->refundMoneyMax));
                                $issue_detail->refundMoneyMaxLocal  = base64_encode(serialize($issue['issue_detail']->resultObject->refundMoneyMaxLocal));

                                $issue_detail->save();
                            }
                        }
                    }
                }
            }else{
                $this->comment($account_name.' hasnot this time OR token is timeout');

            }
        }else{
            $this->comment('account num maybe worng.');

        }
    }
}
