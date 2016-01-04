<?php
/**
 * 渠道账号库
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */

namespace App\Repositories\Channel;

use App\Base\BaseRepository;
use App\Models\Channel\AccountModel;

class AccountRepository extends BaseRepository
{
    protected $searchFields = ['account', 'title'];
    public $rules = [
        'create' => [
            'account' => 'required',
            'prefix' => 'required',
            'title' => 'required',
            'brief' => 'required',
        ],
        'update' => [
            'account' => 'required',
            'prefix' => 'required',
            'title' => 'required',
            'brief' => 'required',
        ]
    ];

    public function __construct(AccountModel $account)
    {
        $this->model = $account;
    }

}