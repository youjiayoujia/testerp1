<?php
namespace App\Models\product;

use App\Base\BaseModel;
use Tool;
class ImageModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['spu_id', 'product_id', 'type', 'path', 'name'];

    public function getSrcAttribute()
    {
        return $this->path . $this->name;
    }

    /**
     * 创建图片(单张)
     *
     * @param $data ['spu_id','product_id','type']
     * @param $files
     * @param string $uploadType
     */
    public function singleCreate($data, $file = null,$key)
    {
        $data['type'] = 'original';
        if ($data['type'] != 'public') {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/' . $data['type'] . '/';
        } else {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['type'] . '/';
        }
        if ($this->valid($file->getClientOriginalName())) {
            $data['name'] = time() . $key . '.' . $file->getClientOriginalExtension();
            $file->move($data['path'], $data['name']);
            $imageModel = $this->create($data);
            return $imageModel->id; 
        }       
    }

    //todo: file size, real mime
    private function valid($fileName)
    {
        $extension = Tool::getFileExtension($fileName);
        return in_array($extension, config('product.image.extensions'));
    }

}
