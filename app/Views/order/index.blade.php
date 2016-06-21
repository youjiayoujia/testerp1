@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="ordernum">订单号</th>
    <th>邮箱</th>
    <th>物流</th>
    <th>收货人</th>
    <th>发货国家/地区</th>
    <th class="sort" data-field="amount">总金额<strong style="color: red">(运费)</strong></th>
    <th>预测毛利率</th>
    <th class="sort" data-field="channel_account_id">渠道账号</th>
    <th>订单状态</th>
    <th>客服人员</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>详情</th>
@stop
@section('tableBody')
    @foreach($data as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->ordernum }}</td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->logistics_id }}</td>
            <td>{{ $order->shipping_firstname . ' ' . $order->shipping_lastname }}</td>
            <td>{{ $order->shipping_country }}</td>
            <td>{{ $order->currency . ' ' . $order->amount }}
                <strong style="color: red">({{ $order->amount_shipping }})</strong>
            </td>
            <td>
                @if($order->gross_margin != null)
                    <div>{{ $order->gross_margin }}</div>
                    <div>产品成本: {{ $order->all_item_cost }}RMB</div>
                    <div>运费成本: {{ $order->packages->sum('cost') }}RMB</div>
                    <div>平台费: {{ '' }}USD</div>
                    <div>毛利润: {{ '' }}USD</div>
                @endif
                @if($order->gross_margin == null)
                    {{ '' }}
                @endif
            </td>
            <td>{{ $order->channelAccount->alias }}</td>
            <td>{{ $order->status_name }}</td>
            <td>{{ $order->userService == null ? '' : $order->userService->name }}</td>
            <td>{{ $order->created_at }}</td>
            <td>
                <a class="btn btn-primary btn-xs" role="button" data-toggle="collapse" href=".collapseExample{{$order->id}}" aria-expanded="true" aria-controls="collapseExample">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
            </td>
        </tr>
        <tr class="collapse in collapseExample{{$order->id}}">
            <td colspan="5" style="padding: 10px; margin: 10px">
                <div>{{ $order->shipping_firstname . ' ' . $order->shipping_lastname }}</div>
                <div>{{ $order->shipping_address . ' ' . $order->shipping_city . ' ' . $order->shipping_state }}</div>
                <div>{{ $order->shipping_country . ' ' . $order->country ? $order->country->name : '' . ' ' . $order->country ? $order->country->cn_name : '' }}</div>
                @if(count($order->refunds) > 0)
                    @foreach($order->refunds as $refund)
                        <div style="color: red">
                            <label>退款ID:</label>{{ $refund->id }}
                            <label>退款金额:</label>{{ $refund->refund_amount }}
                            <label>原因:</label>{{ $refund->reason_name }}
                            <label>申请时间:</label>{{ $refund->created_at }}
                        </div>
                    @endforeach
                @endif
            </td>
            <td colspan="25" style="padding: 10px; margin: 10px">
                @foreach($order->items as $orderItem)
                    @if($orderItem->sku == '' && $orderItem->channel_sku == '')
                    @endif
                    @if($orderItem->sku != '' && $orderItem->item_id != 0)
                        <div class="row">
                            <div class="col-lg-5" style="color: blue">{{ $orderItem->sku .'('. $orderItem->channel_sku .')' }}
                                <strong style="color: black">{{ $orderItem->item->is_sale == 1 ? '可售' : '不可售'}}</strong>
                            </div>
                            <div class="col-lg-3">{{ $orderItem->item->c_name }}</div>
                            <div class="col-lg-2">{{ $order->currency . ' ' . $orderItem->price }}</div>
                            <div class="col-lg-1">{{ 'X' . ' ' . $orderItem->quantity }}</div>
                            <div class="col-lg-1" style="color: #2aabd2">
                                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                                   data-id="{{ $orderItem->id }}"
                                   data-url="{{ route('orderItem.destroy', ['id' => $orderItem->id]) }}">
                                    <span class="glyphicon glyphicon-trash"></span> 删除
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="row col-lg-12" style="color: black; text-align: center;">
                    平台费: ${{ '' }},
                    总运费: {{ $order->packages->sum('cost') }}RMB,
                    包裹重: {{ $order->packages->sum('weight') }}Kg,
                    物品数量: {{ $order->items->sum('quantity') }}
                </div>
            </td>
        </tr>
        <tr class="collapse in collapseExample{{$order->id}}">
            <td colspan="30" style="padding: 10px; margin: 10px">
                <div>
                    <strong>邮箱</strong> : {{ $order->email }}
                    <strong>收款方式</strong> : {{ $order->payment }}
                    <strong>交易号</strong> : {{ $order->transaction_number }}
                    <strong>运输方式</strong> : {{ '(' . ' ' . ')' }}
                    <strong style="color: red">(运费 : {{ $order->currency . ' ' . $order->amount_shipping }})</strong>
                </div>
                <div style="text-align: center;">
                    @if($order->status == 'REVIEW')
                        <a href="javascript:" class="btn btn-success btn-xs review" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 审核
                        </a>
                    @endif
                    @if($order->status == 'PREPARED')
                        <a href="javascript:" class="btn btn-success btn-xs prepared" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 暂停发货
                        </a>
                    @endif
                    @if($order->active != 'NORMAL')
                        <a href="javascript:" class="btn btn-success btn-xs normal" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 恢复正常
                        </a>
                    @endif
                    @if($order->status == 'UNPAID' || $order->status == 'PAID' || $order->status == 'PREPARED' || $order->status == 'REVIEW' || $order->status == 'NEED')
                        <a href="{{ route('order.edit', ['id'=>$order->id]) }}" class="btn btn-warning btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> 编辑
                        </a>
                    @endif
                    @if($order->status == 'UNPAID' || $order->status == 'PAID' || $order->status == 'PREPARED' || $order->status == 'REVIEW')
                        <a href="{{ route('withdraw', ['id'=>$order->id]) }}" class="btn btn-success btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> 撤单
                        </a>
                        <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                           data-id="{{ $order->id }}"
                           data-url="{{ route('order.destroy', ['id' =>$order->id]) }}">
                            <span class="glyphicon glyphicon-pencil"></span> 删除
                        </a>
                    @endif
                    <a href="{{ route('refund', ['id'=>$order->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 退款
                    </a>
                    <a href="{{ route('remark', ['id'=>$order->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 补充备注
                    </a>
                    <a href="{{ route('order.show', ['id'=>$order->id]) }}" class="btn btn-info btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 查看
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 查询订单状态
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'UNPAID']) }}">未付款</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'PAID']) }}">已付款</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'PREPARED']) }}">准备发货</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'NEED']) }}">缺货</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'PACKED']) }}">打包完成</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'SHIPPED']) }}">发货完成</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'COMPLETE']) }}">订单完成</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'CANCEL']) }}">取消订单</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'REVIEW']) }}">需审核</a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 查询售后状态
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ DataList::filtersEncode(['active', '=', 'NORMAL']) }}">正常</a></li>
            <li><a href="{{ DataList::filtersEncode(['active', '=', 'VERIFY']) }}">验证中</a></li>
            <li><a href="{{ DataList::filtersEncode(['active', '=', 'CHARGEBACK']) }}">客户CB</a></li>
            <li><a href="{{ DataList::filtersEncode(['active', '=', 'STOP']) }}">暂停发货</a></li>
            <li><a href="{{ DataList::filtersEncode(['active', '=', 'RESUME']) }}">恢复正常</a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 国家
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($countries as $country)
                <li><a href="{{ DataList::filtersEncode(['shipping_country', '=', $country->code]) }}">{{ $country->cn_name }}</a></li>
            @endforeach
        </ul>
    </div>
    @parent
@stop
@section('childJs')
    <script type="text/javascript">
        $(document).ready(function () {
            //审核
            $('.review').click(function () {
                if (confirm("确认审核?")) {
                    var order_id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('updateStatus') }}",
                        data: {order_id: order_id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                }
            });

            //暂停发货
            $('.prepared').click(function () {
                if (confirm("确认暂停发货?")) {
                    var order_id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('updatePrepared') }}",
                        data: {order_id: order_id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                }
            });

            //恢复正常
            $('.normal').click(function () {
                if (confirm("确认恢复正常?")) {
                    var order_id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('updateNormal') }}",
                        data: {order_id: order_id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            window.location.reload();
                        }
                    });
                }
            });
        });
    </script>
@stop