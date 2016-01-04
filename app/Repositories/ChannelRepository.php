<?php
/**
 * 渠道库
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\ChannelModel;

class ChannelRepository extends BaseRepository
{
    protected $searchFields = ['name'];
    public $rules = [
        'create' => ['name' => 'required|unique:channels,name'],
        'update' => ['name' => 'required|unique:channels,name,{id}']
    ];

    public function __construct(ChannelModel $channel)
    {
        $this->model = $channel;
    }

}