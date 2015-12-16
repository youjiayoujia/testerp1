<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\ProviderModel as Provider;

/**
 * 范例: 供应商库
 *
 * @author MC<nyewon@gmail.com>
 */

class ProviderRepository extends BaseRepository
{
	/*
	*
	* @func 用于查询
	*
	*/
	protected $searchFields = ['name'];

	/**
	*
	* 规则验证
	*
	*/
    public $rules = [
        'create' => ['name' => 'required|unique:providers,name'],
        'update' => []
    ];


	public function __construct(Provider $provider)
	{
		$this->model = $provider;
	}

}