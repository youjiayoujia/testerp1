<?php
/**
 * 图片模型
 *
 * 2016-01-04
 * @author tup<836466300@qq.com>
 */

namespace App\Models\product;

use Storage;
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
            //'model' => 'required',
            //'type' => 'required',
            //'image0' => 'required',
        ],
        'update' => [],
    ];

    public function getSrcAttribute()
    {
        return $this->path . $this->name;
    }

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'product_id','id');
    }

    public function labels()
    {
        return $this->belongsToMany('App\Models\LabelModel','image_labels','image_id','label_id')->withTimestamps();
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
        if (!array_key_exists('type', $data)) {
            $data['type'] = 'original';
        }
        if ($data['type'] != 'public') {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/' . $data['type'] . '/';
        } else {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['type'] . '/';
        }
        if ($this->valid($file->getClientOriginalName())) {
            $data['name'] = time() . $key . '.' . $file->getClientOriginalExtension();
            Storage::disk('product')->put($data['path'].$data['name'],file_get_contents($file->getRealPath()));
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
        /*if ($data['type'] != 'public') {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/' . $data['type'] . '/';
        } else {
            $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['type'] . '/';
        }*/
        $data['path'] = config('product.image.uploadPath') . '/' . $data['spu_id'] . '/' . $data['product_id'] . '/' . $data['is_link'] . '/';
        $disk = Storage::disk('product');
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $key => $file) {
                    if ($this->valid($file->getClientOriginalName())) {
                        $data['name'] = time() . $key . '.' . $file->getClientOriginalExtension();
                        Storage::disk('product')->put($data['path'].$data['name'],file_get_contents($file->getRealPath()));
                        $imageModel = $this->create($data);
                        $arr[] = $data['is_link'];

                        $imageModel->labels()->attach($arr);
                        $imageModel->labels()->attach($data['tag']);
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

        return $imageModel;
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
    public function updateImage($id, $file,$data)
    {
        $image = $this->findOrFail($id);
        //if (is_file($image->src)) {
        //    unlink($image->src);
        //}
        //foreach($data['image_type'] as $type){
         //   $imageModel->labels()->attach($data['image']);
        //}
        //echo '<pre>';
        //$tag_arr = [];
        //$active['is_active']
        //foreach($image->labels as $labels){
        //    $tag_arr[] = $labels->pivot->label_id;
        //    $labels->update()
        //}
        
        //print_r($tag_arr);exit;
        //print_r($image->labels->toArray());exit;
        
        //$arr['is_link'] = $data['is_link'];
        //$arr['active'] = 1;
        //$image->labels()->attach($arr['is_link'],['is_active'=>1]);
        $arr[] = $data['is_link'];
        foreach($data['image_type'] as $data){
            $arr[] = $data;
        }
        
        $image->labels()->sync($arr);
        return;
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
