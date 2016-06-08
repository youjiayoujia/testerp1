<?php
namespace App\Modules\Channel\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/5/17
 * Time: 下午2:51
 */
interface AdapterInterface
{
    /**
     * 获取单个订单
     *
     * @param $orderID
     * @return mixed
     */
    public function getOrder($orderID);

    /**
     * 获取订单列表
     *
     * @param $startDate
     * @param $endDate
     * @param array $status
     * @param int $perPage
     * @return $orderArray
     *
     * 返回数据格式:
     * [
     *      [
     *          'channel_ordernum' => '',
     *          'email' => '',
     *          'amount' => '',
     *          'currency' => '',
     *          'status' => '',
     *          'payment' => '',
     *          'shipping_method' => '',
     *          'shipping_firstname' => '',
     *          'shipping_lastname' => '',
     *          'shipping_address' => '',
     *          'shipping_address1' => '',
     *          'shipping_city' => '',
     *          'shipping_state' => '',
     *          'shipping_country' => '',
     *          'shipping_zipcode' => '',
     *          'shipping_phone' => '',
     *          'payment_date' => '',
     *          'create_time' => '',
     *          'items' => [
     *              [
     *                  'sku' => '',
     *                  'channel_sku' => '',
     *                  'quantity' => '',
     *                  'price' => '',
     *                  'currency' => '',
     *              ],
     *              [
     *                  'sku' => '',
     *                  'channel_sku' => '',
     *                  'quantity' => '',
     *                  'price' => '',
     *                  'currency' => '',
     *              ],
     *          ]
     *      ],
     *      [
     *          Same As above ...
     *      ],
     * ]
     */
    public function listOrders($startDate, $endDate, $status = [], $perPage = 10);

    /**
     * 回传物流号
     *
     */
    public function returnTrack();

}