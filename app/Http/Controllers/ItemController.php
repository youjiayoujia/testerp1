<?php
/**
 * item控制器
 *
 * User: youjia
 * Date: 16/1/18
 * Time: 09:32:00
 */

namespace App\Http\Controllers;

use App\Models\ItemModel;

class ItemController extends Controller
{
    public function __construct(ItemModel $item)
    {
        $this->model = $item;
        $this->mainIndex = route('item.index');
        $this->mainTitle = 'item';
        $this->viewPath = 'item.';
    }

}