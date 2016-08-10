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
use App\Modules\Logistics\Adapter\CoeAdapter;
use App\Modules\Logistics\Adapter\szChinaPostAdapter;
use App\Modules\Logistics\Adapter\WinitAdapter;
use App\Modules\Logistics\Adapter\FpxAdapter;
use App\Modules\Logistics\Adapter\SmtAdapter;

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

    public function createCoeDriver($config)
    {
        return new CoeAdapter($config);
    }
    
    public function createSzChinaPostDriver($config)
    {
        return new SzChinaPostAdapter($config);
    }
    
    public function createWinitDriver($config){
        return new WinitAdapter($config);
    }
    
    public function createFpxDriver($config){
        return new FpxAdapter($config);
    }
    
    public function createSmtDriver($config){
        return new SmtAdapter($config);
    }
}