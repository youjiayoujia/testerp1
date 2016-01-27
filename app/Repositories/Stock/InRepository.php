<?php
/**
 * 入库操作类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/22
 * Time:10:48
 *
 */
namespace App\Repositories\Stock;

use App\Base\BaseRepository;
use App\Models\Stock\InModel;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;

class InRepository extends BaseRepository
{

    
    public function __construct(InModel $stockin)
    {
        $this->model = $stockin;
    }


}