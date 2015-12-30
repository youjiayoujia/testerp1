<?php

namespace App\Repositories\Product;

use App\Base\BaseRepository;
use App\Models\product\ImageModel;
use App\helps\Sort;
use Chumper\Zipper\Zipper;
use Folklore\Image\Facades\Image;

/**
 * 范例: 产品库
 *
 * @author Vincent<nyewon@gmail.com>
 */
class ImageRepository extends BaseRepository
{
    protected $searchFields = ['id', 'product_id', 'user_id', 'type'];
    public $rules = [
        'create' => [
            'product_id' => 'required',
            'type' => 'required',
            'user_id' => 'required',
        ],
        'update' => [
            'product_id' => 'required',
            'type' => 'required',
            'user_id' => 'required',
        ]
    ];


    public function __construct(ImageModel $Image)
    {
        $this->model = $Image;
    }

    /**
     * 上传产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function uploadImage($data, $files)
    {	
        $path = 'product/'.$data['product_id'].'/'.$data['type'].'/';
		$res=$this->model->isImageUpload($data);
		$data['image_path']=$path;
		$data['image_name']='';
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $key=>$file) {
                    if ($file->isValid()) {
						$suffix = $file -> getClientOriginalExtension();
                        $name = $data['product_id'].$data['type'].$key.time().'.'.$suffix;
                        $filePath = $file->move($path, $name);     
                    }
                }
                break;
            case 'zip':
                foreach ($files as $key=>$file) {
                    if ($file->isValid() && $key=='zip') {
						$suffix = $file -> getClientOriginalExtension();
						$name=$data['product_id'].$data['type'].'.'.$suffix;
						$filePath = $file->move('zip/', $name);
						$file->getTargetFile('product/'.$data['product_id'].'/');
						$zipper = new Zipper;
						$res=$zipper->make('zip/'.$name)->extractTo('product/'.$data['product_id'].'/');
		            }
                }
                break;
        }
		$helper=new Sort;
		$images=$helper->get_dirname($path);
		foreach ($images as $key=>$image) {
			$suffix=substr(strrchr($image, '.'), 1);	
			$name = $data['product_id'].$data['type'].$key.time().'.'.$suffix;
			rename($path.$image,$path.$name);
			$data['image_name'] = $name.'#'.$data['image_name'];
		}
		if($res>0){	
		$this->update($res, $data);
		}else{
		$this->create($data);	
			}
    }

    /**
     * 更新产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
/*   public function updateImage($data, $files)
    {
		$id=$data['id'];
		$imageOringalNames=$this->get($id);
		$data['image_path']='';
		$data['image_name']='';
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $key=>$file) {
                    if ($file->isValid()) {
						$suffix = $file -> getClientOriginalExtension();
                        $name = $data['product_id'].$data['type'].$key.time().'.'.$suffix;
                        $filePath = $file->move($path, $name);     
                    }
                }
                break;
            case 'zip':
                foreach ($files as $key=>$file) {
                    if ($file->isValid() && $key=='zip') {
						$suffix = $file -> getClientOriginalExtension();
						$name=$data['product_id'].$data['type'].'.'.$suffix;
						$filePath = $file->move('zip/', $name);
						$file->getTargetFile('product/'.$data['product_id'].'/');
						$zipper = new Zipper;
						$res=$zipper->make('zip/'.$name)->extractTo('product/'.$data['product_id'].'/');
                    }
                }
                break;
        }
		$helper=new Sort;
		$images=$helper->get_dirname($path);
		foreach ($images as $key=>$image) {
			$suffix=substr(strrchr($image, '.'), 1);	
			$name = $data['product_id'].$data['type'].$key.time().'.'.$suffix;
			rename($path.$image,$path.$name);
			if($key>0){
				$data['image_name'] = $name.'#'.$data['image_name'];
			}else{
				$data['image_name'] = $name;
				}
		}
        return $this->update($id, $data);
    }*/
  /**
     * 删除产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function destroyImage($id)
    {
        $result = $this->get($id);
		if(isset($result['image_name'])){
			$imageName=$result['image_name'];
			$images=explode("#",$imageName);
			}
		foreach($images as $image){
			if(!empty($image)){		
			unlink('product/'.$result['product_id'].'/'.$result['type'].'/'.$image);
			}
			}
        $this->destroy($id);
    } 

}
