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
}
