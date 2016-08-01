<?php
namespace App\Modules\Logistics\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/8/1
 * Time: 下午13:29
 */
interface AdapterInterface
{
    /**
     * 物流下单
     * @return mixed
     */
    public function getTracking($data);
}