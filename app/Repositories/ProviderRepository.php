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
        'create' => [   'name' => 'required|max:128|unique:providers,name',
                        'address' => 'required|max:256',
                        'url' => 'required|max:256|active_url',
                        'telephone' => 'required|max:256|digits_between:8,11'],
        'update' => [   'name' => 'required|max:128|unique:providers,name, {id}',
                        'address' => 'required|max:256',
                        'url' => 'required|max:256|active_url',
                        'telephone' => 'required|max:256|digits_between:8,11',]
    ];


	public function __construct(Provider $provider)
	{
		$this->model = $provider;
	}

    public function create($data)
    {
        $provider = $this->model->create($data);
        $provider->update(['detail_address' =>  $data['province'].' '.$data['city']]);
    }

    public function update($id, $data)
    {
        $this->get($id)->update($data);
        $this->get($id)->update(['detail_address' => $data['province'].' '.$data['city']]);
    }
}