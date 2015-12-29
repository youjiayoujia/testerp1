<?php
/**
 * item操作类 
 * 定义规则与一些具体操作 
 *
 * @author MC<178069409@qq.com>
 * Date:15/12/28
 * Time:12:11
 *
 */
namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\ItemModel as Item;

class ItemRepository extends BaseRepository
{
    
    public function __construct(Warehouse $warehouse)
    {
        $this->model = $warehouse;
    }
}