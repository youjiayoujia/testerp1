<?php

/**
 * 产品控制器
 * 处理产品相关的Request与Response
 *
 * User: Vincent
 * Date: 15/11/17
 * Time: 下午5:02
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FashionRepository;

class FashionController extends Controller
{
	protected $fashion;

	function __construct(Request $request, FashionRepository $fashion)
	{
		$this->request = $request;
		$this->fashion = $fashion;
	}

	public function index()
	{
		$this->request->flash();
		$response = [
			'columns' => $this->fashion->column,
			'data' => $this->fashion->index($this->request),
		];

		return view('fashion.index', $response);
	}

	public function show($id)
	{
		$response = [
			'fashion' => $this->fashion->detail($id),
		];

		return view('fashion.show', $response);
	}

	public function create()
	{
		return view('fashion.create');
	}

	public function store()
	{
		$this->request->flash();
		$this->validate($this->request, $this->fashion->rules);
		$patharr = array();
		$buf = array();
		for($i=0; $i < 6; $i++){
				if($_FILES['img1']['tmp_name'] != ' '){
					$patharr[$i] = $_FILES['img'.($i+1)]['tmp_name'];
				}
		}
		
		$path = $_SERVER['DOCUMENT_ROOT'];
		$buf = $this->fashion->movePhotos($this->request->name,$patharr,$path);

		$len = count($buf);
		for($i=0;$i<$len;$i++){
			$name = 'img'.($i+1);
			$this->request->$name = $buf[$i];
		}

		$this->fashion->store($this->request);
		return redirect(route('fashion.index'));
	}

	public function update($id)
	{
		$this->request->flash();
		$this->validate($this->request, $this->fashion->rules);
		$this->fashion->update($id, $this->request);

		return redirect(route('fashion.index'));
	}

	public function edit($id)
	{
		$response = [
			'fashion' => $this->fashion->edit($id),
		];

		return view('fashion.edit', $response);
	}

	public function destroy($id)
	{
		$this->fashion->destroy($id);

		return redirect(route('fashion.index'));
	}

}