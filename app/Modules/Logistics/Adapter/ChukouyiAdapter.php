<?php
namespace App\Modules\Logistics\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/8/1
 * Time: 下午13:31
 */

class ChukouyiAdapter implements AdapterInterface
{
    public function __construct($config)
    {

    }

    public function getTracking($data)
    {
        echo 'here is the chukouyi adapter: Function getTracking';
        // TODO: Implement placeOrder() method.
    }

}