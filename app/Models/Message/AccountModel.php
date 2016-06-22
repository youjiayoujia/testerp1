<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/21
 * Time: 14:16
 */
namespace App\Models\Message;
use App\Base\BaseModel;
class AccountModel extends BaseModel{
    protected $table = 'message_accounts';
    
    protected $fillable = [
        'account',
        'secret',
        'token'
    ];
}