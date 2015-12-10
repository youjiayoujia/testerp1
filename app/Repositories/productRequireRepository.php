<?php

/**
 * 范例: 供应商库
 *
 * @author MC<178069409@qq.com>
 */

namespace App\Repositories;

use Config;
use App\Base\BaseRepository;
use App\Models\productRequireModel as productRequire;

class productRequireRepository extends BaseRepository
{
	public $column = ['id', 'name', 'address', 'similar_sku', 'competition_url', 'remark', 'expected_date', 'needer_id', 'needer_shopid', 'created_by', 'created_at', 'status', 'user_id', 'handle_time'];

	protected $filters = ['name'];

	public $rules = [
		'name' => 'required|max:255|unique:product_require,name',
		'competition_url' => 'active_url',
		'needer_id' => 'required',
		'needer_shop_id' => 'required',
	];

	function __construct(productRequire $productRequire)
	{
		$this->model = $productRequire;
	}

	public function store($request)
	{
		$this->model->name = $request->input('name');
		$this->model->address = $request->input('province')." ".$request->input('city');
		$this->model->similar_sku = $request->input('sku');
		$this->model->competition_url = $request->input('url');
		$this->model->remark = $request->input('remark');
		$this->model->expected_date = $request->input('expdate');
		$this->model->needer_id = $request->input('needer_id');
		$this->model->needer_shop_id = $request->input('needer_shop_id');
		$this->model->created_by = $request->input('created_by');
		$this->model->status = '未处理';
		$this->model->user_id = NULL;
		$this->model->handle_time = NULL;

		$this->model->save();

		$path = '';
		$i=1;
		for( ; $i <= 6; $i++) {
			if($request->hasFile('img'.$i)) {
				$file = $request->file('img'.$i);
				$path = Config::get('product_require_img_path.dir')."/".$this->model->id;
				file_exists($path) or mkdir($path, 644, true);
				$file->move($path,"/".$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.')));
				$name = 'img'.$i;
				$this->model->$name = "/".$path."/".$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.'));
			}
		}

		return $this->model->save();
	}

	public function update($id, $request)
	{
		$res = $this->model->find($id);

		$path = '';
		$i=1;
		for( ; $i <= 6; $i++) {
			if($request->hasFile('img'.$i)) {
				$file = $request->file('img'.$i);
				$path = Config::get('product_require_img_path.dir')."/".$id;
				file_exists($path) or mkdir($path, 644, true);
				if(file_exists($path.'/'.$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.'))))
					unlink($path.'/'.$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.')));
				$file->move($path,$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.')));
				$name = 'img'.$i;
				$res->$name = "/".$path."/".$i.substr($file->getClientOriginalName(),strrpos($file->getClientOriginalName(),'.'));
			}
		}
		$res->name = $request->input('name');
		$res->address = $request->input('province')." ".$request->input('city');
		$res->similar_sku = $request->input('sku');
		$res->competition_url = $request->input('url');
		$res->remark = $request->input('remark');
		$res->expected_date = $request->input('expdate');
		$res->needer_id = $request->input('needer_id');
		$res->needer_shop_id = $request->input('needer_shop_id');
		$res->created_by = $request->input('created_by');

		return $res->save();
	}


}