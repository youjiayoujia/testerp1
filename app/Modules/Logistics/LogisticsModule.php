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
use App\Modules\Logistics\Adapter\ShunyouAdapter;
use App\Modules\Logistics\Adapter\ShunfengAdapter;
use App\Modules\Logistics\Adapter\ShunfenghlAdapter;
use App\Modules\Logistics\Adapter\EubofflineAdapter;
use App\Modules\Logistics\Adapter\EubAdapter;



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

    /** 实例化顺友
     * @param $config
     * @return ShunyouAdapter
     */
    public function createShunyouDriver($config)
    {
        return new ShunyouAdapter($config);
    }

    /** 实例化顺丰俄罗斯
     * @param $config
     * @return ShunfengAdapter
     */
    public function createShunfengDriver($config){
        return new ShunfengAdapter($config);
    }

    /**实例化顺丰荷兰
     * @param $config
     * @return ShunfenghlAdapter
     */
    public function createShunfenghlDriver($config){
        return new ShunfenghlAdapter($config);
    }

    /**实例化线下Eub
     * @param $config
     * @return EubofflineAdapter
     */
    public function createEubofflineDriver($config){
        return new EubofflineAdapter($config);
    }

    /**实例化线上eub
     * @param $config
     * @return EubAdapter
     */
    public function createEubDriver($config){
        return new EubAdapter($config);
    }

}