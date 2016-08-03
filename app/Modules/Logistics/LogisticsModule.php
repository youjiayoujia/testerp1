<?php
namespace App\Modules\Logistics;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/8/1
 * Time: 下午13:27
 */
use Exception;
use App\Modules\Logistics\Adapter\ChukouyiAdapter;

class LogisticsModule
{
    public function driver($adapter, $config)
    {
        $driverMethod = 'create' . ucfirst(strtolower($adapter)) . 'Driver';
        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new Exception("Driver [{$adapter}] not supported.");
        }
    }

    /**
     * 出口易接口驱动
     *
     * @param $config
     * @return ChukouyiAdapter
     */
    public function createChukouyiDriver($config)
    {
        return new ChukouyiAdapter($config);
    }
}