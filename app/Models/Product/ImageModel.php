<?php
/**
 * 图片模型
 *
 * 2016-01-04
 * @author tup<836466300@qq.com>
 */

namespace App\Models\product;

use App\Base\BaseModel;
use Tool;
use Zipper;

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

    protected $searchFields = ['type'];
    public $rules = [
        'create' => [
            'product_id' => 'required',
            'type' => 'required',
        ],
        'update' => [],
    ];

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
    public function singleCreate($data, $file = null, $key)
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

    /**
     * 创建图片
     *
     * @param $data ['spu_id','product_id','type']
     * @param $files
     * @param string $uploadType
     */
    public function imageCreate($data, $files = null)
    {
        if ($data['type'] != 'public') {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/' . $data['type'] . '/';
        } else {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['type'] . '/';
        }
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $key => $file) {
                    if ($this->valid($file->getClientOriginalName())) {
                        $data['name'] = time() . $key . '.' . $file->getClientOriginalExtension();
                        $file->move($data['path'], $data['name']);
                        $this->create($data);
                    }
                }
                break;
            case 'zip':
                foreach ($files as $file) {
                    Tool::dir($data['path']);
                    $zipper = Zipper::make($file->getRealPath());
                    $zipFiles = $zipper->listFiles();
                    foreach ($zipFiles as $key => $name) {
                        if ($this->valid($name)) {
                            $data['name'] = time() . $key . '.' . Tool::getFileExtension($name);
                            file_put_contents($data['path'] . $data['name'], $zipper->getFileContent($name));
                            $this->create($data);
                        }
                    }
                }
                break;
        }
    }

    /**
     * 更新图片
     *
     * @param $id
     * @param $data
     * @param $file
     * @return mixed
     * @throws FileException
     */
    public function updateImage($id, $file)
    {
        $image = $this->findOrFail($id);
        if (is_file($image->src)) {
            unlink($image->src);
        }

        return $file->move($image->path, $image->name);
    }

    /**
     * 删除图片
     *
     * @param int $id
     * @return mixed
     */
    public function imageDestroy($id)
    {
        $image = $this->findOrFail($id);
        if (is_file($image->src)) {
            unlink($image->src);
        }

        return $this->destroy($id);
    }

}
