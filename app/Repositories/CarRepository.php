<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\Brand;
use App\Models\Car;
use Config;

/**
 * CarRepository
 *
 * @author Vincent<nyewon@gmail.com>MTOOL
 */
class CarRepository extends BaseRepository
{

    public function __construct(Car $car)
    {
        $this->model = $car;
    }

    public function index($request)
    {
        $pageSize = $request->input('pageSize') ? $request->input('pageSize') : Config::get('setting.pageSize');
        $results = $this->model
                ->paginate($pageSize);
        return $results;
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
