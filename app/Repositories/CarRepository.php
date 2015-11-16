<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\BrandModel as Brand;
use App\Models\CarModel as Car;
use Config;

/**
 * CarRepository
 *
 * @author Vincent<nyewon@gmail.com>
 */
class CarRepository extends BaseRepository
{
    protected $columns = [
        ['name' => 'id', 'label' => '#'],
        ['name' => 'brand_id', 'label' => '品牌'],
        ['name' => 'size', 'label' => '型号'],
        ['name' => 'color', 'label' => '颜色'],
        ['name' => 'created_at', 'label' => '创建日期']
    ];

    protected $filters = ['size', 'color', 'created_at'];

    public function __construct(Car $car)
    {
        $this->model = $car;
    }

    public function index($request)
    {
        $pageSize = $request->input('pageSize') ? $request->input('pageSize') : Config::get('setting.pageSize');
        $result = $this->filter($request);
        if ($request->has('orderField') AND $request->has('orderDirection')) {
            $result = $result->orderBy($request->input('orderField'), $request->input('orderDirection'));
        } else {
            $result = $result->orderBy('id', 'desc');
        }
        return $result->paginate($pageSize);
    }

    public function store($inputs, $extra)
    {
        return;
    }

    public function edit($id, $extra)
    {
        return;
    }

    public function update($id, $inputs, $extra)
    {
        return;
    }

    public function destroy($id, $extra)
    {
        return;
    }

    public function getAllBrands()
    {
        return Brand::all();
    }

}
