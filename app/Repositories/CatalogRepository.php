<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\Catalog as Catalog;

/**
 * 品类库
 *
 * @author Vincent<nyewon@gmail.com>
 */
class CatalogRepository extends BaseRepository
{
    protected $searchFields = ['name'];
    public $rules = [
        'create' => ['name' => 'required|unique:catalog,name'],
        'update' => []
    ];

    public function __construct(Catalog $catalog)
    {
        $this->model = $catalog;
    }

    public function store($data)
    {
        $catalog = $this->model->create($data);
        if ($data['sets']) {
            foreach ($data['sets'] as $setData) {
                $set = $catalog->sets()->create($setData);
                if ($setData['values']) {
                    foreach ($setData['values'] as $setValueData) {
                        $set->values()->create($setValueData);
                    }
                }
            }
        }

        return $catalog;
    }

    public function update($id, $data)
    {

    }

}
