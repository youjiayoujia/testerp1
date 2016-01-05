<?php
/**
 * 图片库
 *
 * @author Vincent<nyewon@gmail.com>
 */

namespace App\Repositories\Product;

use App\Base\BaseRepository;
use App\Models\product\ImageModel;
use App\helps\Tool;
use Chumper\Zipper\Zipper;

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


    public function __construct(ImageModel $image)
    {
        $this->model = $image;
    }

    /**
     * 上传产品图片
     *
     * @param $data $files
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function createImage($data, $files)
    {
        $uploadPath = config('product.image.uploadPath') . $data['product_id'] . '/' . $data['type'] . '/';//图片上传及保存地址
        $temporaryPath = config('product.image.temporaryPath') . $data['product_id'] . '/';//解压图片临时存放地址
        $uploadZip = config('product.image.uploadZip');//压缩包上传地址
        $data['path'] = $uploadPath;
        $data['name'] = '';
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $key => $file) {
                    if ($file->isValid()) {
                        $suffix = $file->getClientOriginalExtension();
                        $name = $data['product_id'] . $data['type'] . $key . time() . '.' . $suffix;
                        $file->move($uploadPath, $name);
                        $data['name'] = $name;
                        $this->create($data);
                    }
                }
                break;
            case 'zip':
                foreach ($files as $key => $file) {
                    if ($file->isValid() && $key == 'zip') {
                        $suffix = $file->getClientOriginalExtension();
                        $name = $data['product_id'] . $data['type'] . '.' . $suffix;
                        $file->move($uploadZip, $name);
                        $file->getTargetFile($temporaryPath);
                        $file->getTargetFile($uploadPath);
                        $zipper = new Zipper;
                        $zipper->make($uploadZip . $name)->extractTo($temporaryPath);
                        $images = Tool::getDirName($temporaryPath . $data['type'] . '/');
                        foreach ($images as $key => $image) {
                            $suffix = substr(strrchr($image, '.'), 1);
                            $name = $data['product_id'] . $data['type'] . $key . time() . '.' . $suffix;
                            $from = $temporaryPath . $data['type'] . '/' . $image;
                            $to = $uploadPath . $name;
                            copy($from, $to);
                            unlink($from);
                            $data['name'] = $name;
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
     * @param $data $file
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function updateImage($data, $file)
    {
        $id = $data['id'];
        $imageOringinal = $this->get($id);
        if ($data['type'] == $imageOringinal['type']) {
            if ($file->isValid()) {
                //更改单张图片
                unlink($imageOringinal['path'] . $imageOringinal['name']);
                $suffix = $file->getClientOriginalExtension();
                $name = $imageOringinal['product_id'] . $imageOringinal['type'] . time() . '.' . $suffix;
                $file->move($imageOringinal['path'], $name);
                $data['name'] = $name;
                return $this->update($id, $data);
            }
        } else {
            if (isset($file)) {
                echo '操作错误！';
            } else {
                //更改图片类型
                $suffix = substr(strrchr($imageOringinal['name'], '.'), 1);
                $name = $data['product_id'] . $data['type'] . time() . '.' . $suffix;
                $path = config('product.image.uploadPath') . $data['product_id'] . '/' . $data['type'] . '/';//图片上传及保存地址
                if (!is_dir($path)) {
                    if (false === @mkdir($path, 0777, true) && !is_dir($path)) {
                        throw new FileException(sprintf('Unable to create the "%s" directory', $path));
                    }
                } elseif (!is_writable($path)) {
                    throw new FileException(sprintf('Unable to write in the "%s" directory', $path));
                }
                $from = $imageOringinal['path'] . $imageOringinal['name'];
                $to = $path . $name;
                copy($from, $to);
                unlink($from);
                $data['path'] = $path;
                $data['name'] = $name;
                return $this->update($id, $data);
            }

        }
    }


    /**
     * 删除产品图片
     *
     * @param int $id 图片ID
     * @param object $request HTTP请求对象
     * @return bool
     */
    public function destroyImage($id)
    {
        $result = $this->get($id);
        unlink($result['path'] . $result['name']);
        return $this->destroy($id);
    }

}
