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
    protected $searchFields = ['type'];
    public $rules = [
        'create' => [
            'product_id' => 'required',
            'type' => 'required',
        ],
        'update' => [],
    ];

    public function __construct(ImageModel $image)
    {
        $this->model = $image;
    }

    /**
     * 添加图片
     *
     * @param $data
     * @param $files
     */
    public function createImage($data, $files)
    {
        $data['path'] = config('product.image.uploadPath') . '/' . $data['product_id'] . '/' . $data['type'] . '/';//图片上传及保存地址
        $temporaryPath = config('product.image.temporaryPath') . '/' . $data['product_id'] . '/';//解压图片临时存放地址
        $uploadZip = config('product.image.zipPath') . '/';//压缩包上传地址
        switch ($data['uploadType']) {
            case 'image':
                foreach ($files as $key => $file) {
                    if ($file->isValid()) {
                        $data['name'] = time() . $key . '.' . $file->getClientOriginalExtension();
                        $file->move($data['path'], $data['name']);
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
     * @param $id
     * @param $data
     * @param $file
     * @return mixed
     * @throws FileException
     */
    public function updateImage($id, $file)
    {
        $image = $this->get($id);
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
    public function destroy($id)
    {
        $image = $this->get($id);
        if (is_file($image->src)) {
            unlink($image->src);
        }

        return $image->delete();
    }

}
