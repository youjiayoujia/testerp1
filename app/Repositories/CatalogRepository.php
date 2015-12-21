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
    protected $rules = [
        'create' => ['name' => 'required|unique:catalogs,name'],
        'update' => ['name' => 'required|unique:catalogs,name,{id}'],
    ];

    public function __construct(Catalog $catalog)
    {
        $this->model = $catalog;
    }
}
