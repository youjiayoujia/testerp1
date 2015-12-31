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
    public function createImage($data, $files)
    {	
        $path = 'product/'.$data['product_id'].'/'.$data['type'].'/';
		$data['image_path']=$path;
		$data['image_name']='';
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $key=>$file) {
                    if ($file->isValid()) {
						$suffix = $file -> getClientOriginalExtension();
                        $name = $data['product_id'].$data['type'].$key.time().'.'.$suffix;
                        $filePath = $file->move($path, $name);
						$data['image_name'] = $name; 
						$this->create($data);    
                    }
                }
                break;
            case 'zip':
                foreach ($files as $key=>$file) {
                    if ($file->isValid() && $key=='zip') {
						$suffix = $file -> getClientOriginalExtension();
						$name=$data['product_id'].$data['type'].'.'.$suffix;
						$filePath = $file->move('zip/', $name);
						$file->getTargetFile('producttemporary/'.$data['product_id'].'/');
						$file->getTargetFile('product/'.$data['product_id'].'/'.$data['type'].'/');
						$zipper = new Zipper;
						$res=$zipper->make('zip/'.$name)->extractTo('producttemporary/'.$data['product_id'].'/');
						$helper=new Sort;
						$images=$helper->get_dirname('producttemporary/'.$data['product_id'].'/'.$data['type'].'/');
						foreach ($images as $key=>$image) {
						$suffix=substr(strrchr($image, '.'), 1);	
						$name = $data['product_id'].$data['type'].$key.time().'.'.$suffix;
						$from='producttemporary/'.$data['product_id'].'/'.$data['type'].'/'.$image;
						$to=$path.$name;
						copy($from,$to);
						unlink($from);
						$data['image_name'] = $name;		
						$this->create($data); 
		            }
					
                }
                break;
        }
		
		}
    }
	/**
     * 更新产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function updateImage($data, $file)
    {	//print_r($file);exit;
		$id=$data['id'];
        $imageOringinal = $this->get($id);
		if($data['type']==$imageOringinal['type']){
			if($file->isValid()) {
				//更改单张图片
				unlink($imageOringinal['image_path'].$imageOringinal['image_name']);
				$suffix = $file -> getClientOriginalExtension();
				$name = $imageOringinal['product_id'].$imageOringinal['type'].time().'.'.$suffix;
				$filePath = $file->move($imageOringinal['image_path'], $name);
				$data['image_name'] = $name;
				$this->update($id, $data);
			}
		}else{
			if(isset($file)){
				echo '操作错误！';
			}else{
				//更改图片类型
				$suffix=substr(strrchr($imageOringinal['image_name'], '.'), 1);
				$name=$data['product_id'].$data['type'].time().'.'.$suffix;
				$path='product/'.$data['product_id'].'/'.$data['type'].'/';
				if (!is_dir($path)) {
					if (false === @mkdir($path, 0777, true) && !is_dir($path)) {
						throw new FileException(sprintf('Unable to create the "%s" directory', $path));
					}
				} elseif (!is_writable($path)) {
					throw new FileException(sprintf('Unable to write in the "%s" directory', $path));
				}			
				$from=$imageOringinal['image_path'].$imageOringinal['image_name'];
				$to=$path.$name;
				copy($from,$to);
				unlink($from);
				$data['image_path']=$path;
				$data['image_name'] = $name;
				$this->update($id, $data);	
			}	
				
		}
    }
 
	
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
		unlink($result['image_path'].$result['image_name']);
        $this->destroy($id);
    } 

}
