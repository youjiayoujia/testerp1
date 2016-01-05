<?php

namespace App\Repositories\Product;

use App\Base\BaseRepository;
use App\Models\product\Product_imageModel;
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


    public function __construct(Product_imageModel $product_image)
    {
        $this->model = $product_image;
    }

    /**
     * 上传产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function store($data, $files)
    {
        $path = 'path';
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $name = 'name';
                        $filePath = $file->move($path, $name);
                        $data['path'] = $filePath;
                        $this->create($data);
                    }
                }
                break;
            case 'zip':
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        //TODO:unzip zip file
                        foreach ($images as $image) {
                            $name = 'name';
                            $filePath = $file->move($path, $name);
                            $data['path'] = $filePath;
                            $this->create($data);
                        }
                    }
                }
                break;
        }
    }

    /**
     * 更新产品图片
     *
     * @param int $id 产品ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function update($id, $data, $file)
    {
        $image = $this->get($id);
        $path = $image->path;
        $name = $image->name;
        if ($file->isValid()) {
            $file->move($path, $name);
        }
        return $image->update($path, $name);
    }

    public function destroy($id)
    {
        $image = $this->get($id);
        $path = $image->path;
        $name = $image->name;
        unlink($path . '/' . $name);
        $image->destroy();
    }

}
