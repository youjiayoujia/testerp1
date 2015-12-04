<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\FashionModel as Fashion;

/**
 * 范例: 供应商库
 *
 * @author MC<nyewon@gmail.com>
 */

class FashionRepository extends BaseRepository
{
	public $column = ['id', 'img1', 'img2', 'img3', 'img4', 'img5', 'img6', 'name', 'address', 'similar_sku', 'competition_url', 'remark', 'expected_date', 'needer_id', 'needer_shopid', 'status', 'user_id', 'handle_time', 'created_at'];

	protected $filters = ['name'];

	public $rules = [
		'name' => 'required|max:255',
		'competition_url' => 'active_url',
		'needer_id' => 'required',
		'needer_shopid' => 'required',
	];

	function __construct(Fashion $fashion)
	{
		$this->model = $fashion;
	}

	public function store($request)
	{
		$this->model->img1 = $request->img1;
		$this->model->img2 = $request->img2;
		$this->model->img3 = $request->img3;
		$this->model->img4 = $request->img4;
		$this->model->img5 = $request->img5;
		$this->model->img6 = $request->img6;
		$this->model->name = $request->input('name');
		$this->model->address = $request->input('address');
		$this->model->similar_sku = $request->input('sku');
		$this->model->competition_url = $request->input('url');
		$this->model->remark = $request->input('remark');
		$this->model->expected_date = $request->input('expdate');
		$this->model->needer_id = $request->input('needer_id');
		$this->model->needer_shopid = $request->input('needer_shopid');
		$this->model->status = $request->input('status');
		$this->model->user_id = $request->input('user_id');
		$this->model->handle_time = $request->input('handle_time');

		
		return $this->model->save();
	}

	/*
	*
	*
	* $dirname   目录名，这边匹配id
	* $arr 里面存放传进来的图片的临时存放目录
	* $path 根目录
	*
	*/
	public function movePhotos($dirname, $arr, $path)
	{
		$len = count($arr);
		$buf = array();
		$i = 0;
		if(!is_dir($path.'/photo/'.$dirname)) {
			mkdir($path.'/photo/'.$dirname,644,true);
		}
		$fd = opendir($path.'/photo/'.$dirname);
			while(($buf = readdir($fd)) !== FALSE) {
				if($buf != '.' && $buf != '..') {
					@unlink($buf);
				}
			}
		closedir($fd);
		foreach($arr as $value) {
			if($value != ''){
				$name = ($i+1).'.jpg';
			 	move_uploaded_file($value,$path.'/photo/'.$dirname.'/'.$name);
				$buf[$i] = '/photo/'.$dirname.'/'.$name;
			}
			$i++;
		}

		return $buf;
	}

	public function update($id, $request)
	{
		$res = $this->model->find($id);

		$res->img1 = $request->img1;
		$res->img2 = $request->img2;
		$res->img3 = $request->img3;
		$res->img4 = $request->img4;
		$res->img5 = $request->img5;
		$res->img6 = $request->img6;
		$res->name = $request->input('name');
		$res->address = $request->input('address');
		$res->similar_sku = $request->input('sku');
		$res->competition_url = $request->input('url');
		$res->remark = $request->input('remark');
		$res->expected_date = $request->input('expdate');
		$res->needer_id = $request->input('neederid');
		$res->needer_shopid = $request->input('needershopid');
		$res->status = $request->input('status');
		$res->user_id = $request->input('userid');
		$res->handle_time = $request->input('handletime');

		return $res->save();
	}


}