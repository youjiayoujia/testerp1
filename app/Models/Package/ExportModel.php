<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;
use Excel;

class ExportModel extends BaseModel
{
    protected $table = 'export_packages';

    protected $fillable = [
        'name'
    ];

    public function items()
    {
    	return $this->hasMany('App\Models\Package\ExportItemModel', 'parent_id', 'id');
    }

    public function inFields($name)
    {
    	$fields = $this->items;
    	foreach($fields as $field) {
    		if($name == $field->name) {
    			return $field->level;
    		}
    	}
    	
    	return false;
    }

    public function extra()
    {
        return $this->hasMany('App\Models\Package\ExtraModel', 'parent_id', 'id');
    }

    // public function calArray($packages, $buf, $arr)
    // {
    //     $rows = [];
    //     foreach($packages as $k => $package) {
    //         foreach($arr as $key => $value) {
    //             switch($value) {
    //                 case 'channel_id':
    //                     $rows[$k][$buf['channel_id']] = iconv('utf-8','gb2312',$package->channel ? $package->channel->name : '渠道有误');
    //                     break;
    //                 case 'channel_account_id':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['channel_account_id'])] = iconv('utf-8','gb2312',$package->channelAccount ? $package->channelAccount->name : '渠道账号有误');
    //                     break;
    //                 case 'order_id':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['order_id'])] =iconv('utf-8','gb2312',$package->order ? $package->order->ordernum : '订单号有误');
    //                     break;
    //                 case 'warehouse_id':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['warehouse_id'])] =iconv('utf-8','gb2312',$package->warehouse ? $package->warehouse->name : '仓库有误');
    //                     break;
    //                 case 'logistics_id':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['logistics_id'])] = iconv('utf-8','gb2312',$package->logistics ? $package->logistics->code : '物流有误');
    //                     break;
    //                 case 'shipper_id':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['shipper_id'])] = iconv('utf-8','gb2312',$package->shipperName ? $package->shipperName->name : '发货人有误');
    //                     break;
    //                 case 'type':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['type'])] = iconv('utf-8','gb2312',$package->type == 'SINGLE' ? '单单' : ($package->type == 'SINGLEMULTI' ? '单多' : '多多'));
    //                     break;
    //                 case 'cost':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['cost'])] = iconv('utf-8','gb2312',$package->cost + $package->cost1);
    //                     break;
    //                 case 'weight':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['weight'])] = iconv('utf-8','gb2312',$package->weight);
    //                     break;
    //                 case 'actual_weight':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['actual_weight'])] = iconv('utf-8','gb2312',$package->actual_weight);
    //                     break;
    //                 case 'tracking_no':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['tracking_no'])] = iconv('utf-8','gb2312',$package->tracking_no);
    //                     break;
    //                 case 'tracking_link':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['tracking_link'])] = iconv('utf-8','gb2312',$package->tracking_link);
    //                     break;
    //                 case 'is_remark':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['is_remark'])] = iconv('utf-8','gb2312',$package->is_remark == 1 ? '是' : '否');
    //                     break;
    //                 case 'is_upload':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['is_upload'])] = iconv('utf-8','gb2312',$package->is_upload == 1 ? '是' : '否');
    //                     break;
    //                 case 'shipping_name':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['shipping_name'])] = iconv('utf-8','gb2312',$package->shipping_name);
    //                     break;
    //                 case 'shipping_address':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['shipping_address'])] = iconv('utf-8','gb2312',$package->shipping_address);
    //                     break;
    //                 case 'shipping_address1':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['shipping_address1'])] = iconv('utf-8','gb2312',$package->shipping_address1);
    //                     break;
    //                 case 'shipping_city':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['shipping_city'])] = iconv('utf-8','gb2312',$package->shipping_city);
    //                     break;
    //                 case 'shipping_state':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['shipping_state'])] = iconv('utf-8','gb2312',$package->shipping_state);
    //                     break;
    //                 case 'shipping_zipcode':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['shipping_zipcode'])] = iconv('utf-8','gb2312',$package->shipping_zipcode);
    //                     break;
    //                 case 'shipping_phone':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['shipping_phone'])] = iconv('utf-8','gb2312',$package->shipping_phone);
    //                     break;
    //                 case 'remark':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['remark'])] = iconv('utf-8','gb2312',$package->remark);
    //                     break;
    //                 case 'printed_at':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['printed_at'])] = iconv('utf-8','gb2312',$package->printed_at);
    //                     break;
    //                 case 'delieved_at':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['delieved_at'])] = iconv('utf-8','gb2312',$package->delieved_at);
    //                     break;
    //                 case 'status':
    //                     $rows[$k][iconv('utf-8','gb2312',$buf['status'])] = iconv('utf-8','gb2312',config('package')[$package->status]);
    //                     break;
    //             }
    //         }
    //     }

    //     return $rows;
    // }

    public function processGoods($file)
    {
        $path = config('setting.excelPath');
        !file_exists($path.'excelProcess.xls') or unlink($path.'excelProcess.xls');
        $file->move($path, 'excelProcess.xls');
        $path = $path.'excelProcess.xls';
        $data = Excel::load($path, function($reader){
            return $reader->all()->toarray(); 
        })->toarray();
        foreach($data as $key => $value) {
            $arr[$key] = $value['tracking_no'];
        }
        return $arr;
    }

    public function calArray($packages, $buf, $arr, $extras)
    {
        $rows = [];
        foreach($packages as $k => $package) {
            foreach($arr as $key => $value) {
                switch($value) {
                    case 'channel_id':
                        $rows[$k][$buf['channel_id']] = $package->channel ? $package->channel->name : '渠道有误';
                        break;
                    case 'channel_account_id':
                        $rows[$k][$buf['channel_account_id']] = $package->channelAccount ? $package->channelAccount->name : '渠道账号有误';
                        break;
                    case 'order_id':
                        $rows[$k][$buf['order_id']] =$package->order ? $package->order->ordernum : '订单号有误';
                        break;
                    case 'warehouse_id':
                        $rows[$k][$buf['warehouse_id']] =$package->warehouse ? $package->warehouse->name : '仓库有误';
                        break;
                    case 'logistics_id':
                        $rows[$k][$buf['logistics_id']] = $package->logistics ? $package->logistics->code : '物流有误';
                        break;
                    case 'shipper_id':
                        $rows[$k][$buf['shipper_id']] = $package->shipperName ? $package->shipperName->name : '发货人有误';
                        break;
                    case 'type':
                        $rows[$k][$buf['type']] = $package->type == 'SINGLE' ? '单单' : ($package->type == 'SINGLEMULTI' ? '单多' : '多多');
                        break;
                    case 'cost':
                        $rows[$k][$buf['cost']] = $package->cost + $package->cost1;
                        break;
                    case 'weight':
                        $rows[$k][$buf['weight']] = $package->weight;
                        break;
                    case 'actual_weight':
                        $rows[$k][$buf['actual_weight']] = $package->actual_weight;
                        break;
                    case 'tracking_no':
                        $rows[$k][$buf['tracking_no']] = $package->tracking_no;
                        break;
                    case 'tracking_link':
                        $rows[$k][$buf['tracking_link']] = $package->tracking_link;
                        break;
                    case 'is_mark':
                        $rows[$k][$buf['is_mark']] = $package->is_mark == 1 ? '是' : '否';
                        break;
                    case 'is_upload':
                        $rows[$k][$buf['is_upload']] = $package->is_upload == 1 ? '是' : '否';
                        break;
                    case 'shipping_name':
                        $rows[$k][$buf['shipping_name']] = $package->shipping_name;
                        break;
                    case 'shipping_address':
                        $rows[$k][$buf['shipping_address']] = $package->shipping_address;
                        break;
                    case 'shipping_address1':
                        $rows[$k][$buf['shipping_address1']] = $package->shipping_address1;
                        break;
                    case 'shipping_city':
                        $rows[$k][$buf['shipping_city']] = $package->shipping_city;
                        break;
                    case 'shipping_state':
                        $rows[$k][$buf['shipping_state']] = $package->shipping_state;
                        break;
                    case 'shipping_zipcode':
                        $rows[$k][$buf['shipping_zipcode']] = $package->shipping_zipcode;
                        break;
                    case 'shipping_phone':
                        $rows[$k][$buf['shipping_phone']] = $package->shipping_phone;
                        break;
                    case 'remark':
                        $rows[$k][$buf['remark']] = $package->remark;
                        break;
                    case 'printed_at':
                        $rows[$k][$buf['printed_at']] = $package->printed_at;
                        break;
                    case 'delieved_at':
                        $rows[$k][$buf['delieved_at']] = $package->delieved_at;
                        break;
                    case 'status':
                        $rows[$k][$buf['status']] = config('package')[$package->status];
                        break;
                    case 'id':
                        $rows[$k][$buf['id']] = $package->id;
                        break;
                    case 'shipping_country':
                        $rows[$k][$buf['shipping_country']] = $package->shipping_country;
                        break;
                    case 'shipping_cn_country':
                        $rows[$k][$buf['shipping_cn_country']] = $package->country ? $package->country->cn_name : '';
                        break;
                    case 'shipping_enall_country':
                        $rows[$k][$buf['shipping_enall_country']] = $package->country ? $package->country->name : '';
                        break;
                    case 'shipped_at':
                        $rows[$k][$buf['shipped_at']] = $package->shipped_at;
                        break;
                    case 'shipping_type':
                        $rows[$k][$buf['shipping_type']] = $package->logistics ? $package->logistics->type : '';
                        break;
                    case 'amount':
                        $rows[$k][$buf['amount']] = $package->total_price;
                        break;
                    case 'buyer_id':
                        $rows[$k][$buf['buyer_id']] = $package->order ? $package->order->by_id : '';
                        break;
                    case 'currency':
                        $rows[$k][$buf['currency']] = $package->order ? $package->order->currency : '';
                        break;
                    case 'expected_logistics_fee':
                        $rows[$k][$buf['expected_logistics_fee']] = $package->calculateLogisticsFee();
                        break;
                    case 'double_check':
                        $rows[$k][$buf['double_check']] = $package->status == 'SHIPPED' ? '2' : '1';
                        break;
                    case 'sku_en':
                        $rows[$k][$buf['sku_en']] = $package->getDeclaredInfo()['declared_en'];
                        break;
                    case 'sku_cn':
                        $rows[$k][$buf['sku_cn']] = $package->getDeclaredInfo()['declared_cn'];
                        break;
                    case 'sku_quantity':
                        $rows[$k][$buf['sku_quantity']] = $package->items()->count();
                        break;
                    case 'sku_cost':
                        $rows[$k][$buf['sku_cost']] = $package->single_price;
                        break;
                    case 'sku_declared_value':
                        $rows[$k][$buf['sku_declared_value']] = $package->getDeclaredInfo()['declared_value'];
                        break;
                    case 'sku_all_quantity':
                        $rows[$k][$buf['sku_all_quantity']] = $package->items()->sum('quantity');
                        break;
                    case 'sku_and_quantity':
                        $rows[$k][$buf['sku_and_quantity']] = $package->sku_info;
                        break;
                }
            }
            foreach($extras as $k1 => $v1) {
                $rows[$k][$v1['name']] = $v1['value'];
            }
        }

        return $rows;
    }
}