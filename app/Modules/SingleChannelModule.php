<?php
/**
 * 获取单个渠道实例，可调用相对应的接口 
 *
 * @author mc<178069409@qq.com>
 * Date: 2016-04-22 15:00
 *
 */
namespace App\Modules;

use App\Modules\Channels\Amazon\AmazonModule;

class SingleChannelModule
{
    //保存渠道对象
    private $model;


    //初始化渠道对象 
    function __construct($channel)
    {
        $module = "App\\Modules\\Channels\\".$channel."\\".$channel.'Module';
        $this->model = new $module();
    }

    /**
     * 抓取订单接口 
     *
     * @return
     *
     */
    public function getOrder()
    {
        $this->model->getOrder();
    }

    /**
     * 订单列表 
     *
     * @return
     *
     */
    public function listOrders()
    {
        $this->model->listOrders();
    }

    /**
     * 订单详情 
     *
     * @return
     *
     */
    public function listOrderItems()
    {
        $this->model->listOrderItems();
    }
}