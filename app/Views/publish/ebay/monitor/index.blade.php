<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-08-19
 * Time: 15:50
 */
?>
@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"></th>
    <th class="sort text-center" data-field="id">ID</th>
    <th class="text-center">站点</th>
    <th class="text-center">帐号</th>
    <th class="text-center">ItemID</th>
    <th class="text-center">标题</th>
    <th class="text-center">无货在线</th>
    <th class="text-center">EbaySku</th>
    <th class="text-center">物品名称</th>
    <th class="text-center">Local</th>
    <th class="sort text-center" data-field="start_time">刊登时间</th>
    <th class="text-center">刊登人</th>
    <th class="text-center">是否在线</th>
    <th class="sort text-center" data-field="start_price">价格</th>
    <th class="text-center">运费</th>
    <th class="sort text-center" data-field="quantity_sold">销量</th>
    <th class="text-center">在线数量</th>
    <th class="text-center">PayPal</th>
    <th class="text-center">处理天数</th>
    {{--
        <th class="sort" data-field="created_at">创建时间</th>
    --}}
    {{--<th>日志</th>--}}
@stop

@section('tableBody')
    @foreach($data as $detail)
        <tr class="text-center">
            <td><input type='checkbox' name='tribute_id'  value="{{ $detail->id }}"></td>
            <td>{{ $detail->id }}</td>
            <td>{{ $detail->ebayProduct->site_name}}</td>
            <td>{{ $detail->ebayProduct->channelAccount->account}}</td>
            <td><a  target=_blank href="{{$detail->ebayProduct->view_item_url}}">{{ $detail->item_id}}</a></td>
            <td>{{ $detail->ebayProduct->title}}</td>
            <td>{{$detail->ebayProduct->EbayOutControl}}</td>
            <td>{{ $detail->sku}}</td>
            <td>
            @if(isset($detail->erpProduct->c_name))
                  {{$detail->erpProduct->c_name}}
            @endif
            </td>
            <td>{{ $detail->ebayProduct->location}}</td>
            <td>{{ $detail->ebayProduct->start_time}}</td>
            <td>
                @if(isset( $detail->operator->name))
                    {{ $detail->operator->name}}
                @endif
            </td>
            <td>{{$detail->EbayStatus}}</td>
            <td>{{ $detail->ebayProduct->currency.' '.$detail->start_price}}</td>
            <td>
                @if(!empty($detail->ebayProduct->shipping_details))
                    <?php
                    $shipping_details = json_decode($detail->ebayProduct->shipping_details);
                    if (!empty($shipping_details->Shipping)) {
                        foreach ($shipping_details->Shipping as $ship) {
                            echo $ship->ShippingService . ': ' . $ship->ShippingServiceCost.'<br/>';
                        }
                    }
                    if (!empty($shipping_details->InternationalShipping)) {
                        foreach ($shipping_details->InternationalShipping as $ship) {
                            echo $ship->ShippingService . ': ' . $ship->ShippingServiceCost.'<br/>';
                        }
                    }
                    ?>
                @endif
            </td>
            <td>{{ $detail->quantity_sold}}</td>
            <td>{{ $detail->quantity}}</td>
            <td>{{ $detail->ebayProduct->paypal_email_address}}</td>


            <td>{{ $detail->ebayProduct->dispatch_time_max}}</td>
          {{--  <td>
               <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse"
                        data-target=".packageDetails{{$detail->id}}" aria-expanded="false"
                        aria-controls="collapseExample">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </button>
            </td>--}}
        </tr>
    @endforeach
    <div class="modal fade" id="sku_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">sku库存信息</div>
                    <div class="panel-body">
                        <div class='buf'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="split" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">拆分包裹</div>
                    <div class="panel-body">
                        <div class='row'>
                            <div class='col-lg-5'>
                                <input type='text' class='form-control package_num' placeholder='需要拆分的包裹数'>
                            </div>
                            <div class='col-lg-1'>
                                <button type='button' class='btn btn-primary confirm_quantity' name=''>确认</button>
                            </div>
                        </div>
                        <div class='split_package'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class='batchedit' data-name="changeOutOfStock">开启无货在线</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changeItemQuantity">设置Item数量</a></li>
            <li><a href="javascript:" class='batchedit' data-name="changePrice">修改价格</a></li>
            <li><a href="javascript:" class='batchedit' data-name="updateShipFee">修改运费</a></li>
            <li><a href="javascript:" class='batchedit' data-name="endItems">批量下架</a></li>
            <li><a href="javascript:" class='batchedit' data-name="modifyPayPalEmailAddress">批量变更paypal</a></li>
            <li><a href="javascript:" class='batchedit' data-name="modifyProcessingDays">批量需要处理天数</a></li>
        </ul>
    </div>
@stop

@section('childJs')
    <script type="text/javascript">
        $('.batchedit').click(function () {
            var checkbox = document.getElementsByName("tribute_id");
            var ids = "";
            var param = $(this).data("name");
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                ids += checkbox[i].value + ",";
            }
            ids = ids.substr(0, (ids.length) - 1);
            var url = "{{ route('ebay.productBatchEdit') }}";
            window.location.href = url + "?ids=" + ids + "&param=" + param;
        });

        function quanxuan() {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }
    </script>
@stop

