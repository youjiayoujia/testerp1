<?php
/**
 * 物流方式库
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午5:42
 */

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\LogisticsModel;

class LogisticsRepository extends BaseRepository
{
    protected $searchFields = ['short_code', 'logistics_type', 'logistics_supplier_id', 'type'];

    public $rules = [
        'create' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'shipping' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'api_docking' => 'required',
            'is_enable' => 'required',
        ],
        'update' => [
            'short_code' => 'required',
            'logistics_type' => 'required',
            'shipping' => 'required',
            'warehouse_id' => 'required',
            'logistics_supplier_id' => 'required',
            'type' => 'required',
            'url' => 'required',
            'api_docking' => 'required',
            'is_enable' => 'required',
        ],
    ];

    public function __construct(LogisticsModel $logistics)
    {
        $this->model = $logistics;
    }

    /**
     * 批量倒入号码池
     *
     * @param $file 导入所需的Excel文件
     *
     */
    public function batchImport($file)
    {
        $filePath = '' . $file;
        Excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });
    }

}