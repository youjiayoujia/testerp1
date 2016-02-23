<?php
/**
 * 国家库
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/8
 * Time: 下午3:26
 */

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Models\CountryModel;

class CountryRepository extends BaseRepository
{
    public function __construct(CountryModel $country)
    {
        $this->model = $country;
    }
}