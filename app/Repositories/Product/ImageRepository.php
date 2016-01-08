<?php
/**
 * 图片库
 *
 * @author Vincent<nyewon@gmail.com>
 */

namespace App\Repositories\Product;

use App\Base\BaseRepository;
use App\Models\product\ImageModel;
use Tool;
use Zipper;

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
    public function create($data, $files = null)
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
                        $this->model->create($data);
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
                            $this->model->create($data);
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
