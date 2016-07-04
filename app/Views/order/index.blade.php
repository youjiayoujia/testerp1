@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="ordernum">订单号</th>
    <th class="sort" data-field="channel">渠道</th>
    <th class="sort" data-field="channel_account_id">渠道账号</th>
    <th>邮箱</th>
    <th>物流</th>
    <th>收货人</th>
    <th>国家</th>
    <th class="sort" data-field="amount">总金额</th>
    <th class="sort" data-field="amount_shipping"><strong class="text-danger">运费</strong></th>
    <th>预测毛利率</th>
    <th>订单状态</th>
    <th>客服人员</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>详情</th>
@stop
@section('tableBody')
    @foreach($data as $order)
        <tr class="{{ $order->status_color }}">
            <td>{{ $order->id }}</td>
            <td>{{ $order->ordernum }}</td>
            <td>{{ $order->channel ? $order->channel->name : '' }}</td>
            <td>{{ $order->channelAccount ? $order->channelAccount->alias : '' }}</td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->shipping }}</td>
            <td>{{ $order->shipping_firstname . ' ' . $order->shipping_lastname }}</td>
            <td>{{ $order->shipping_country }}</td>
            <td>{{ $order->currency . ' ' . $order->amount }}</td>
            <td><strong class="text-danger">{{ $order->currency . ' ' . $order->amount_shipping }}</strong></td>
            <td>
                @if($order->gross_margin)
                    <div>{{ $order->gross_margin }}</div>
                    <div>产品成本: {{ $order->all_item_cost }} RMB</div>
                    <div>运费成本: {{ $order->packages->sum('cost') }} RMB</div>
                    <div>平台费: {{ '' }}USD</div>
                    <div>毛利润: {{ '' }}USD</div>
                @endif
            </td>
            <td>{{ $order->status_name }}</td>
            <td>{{ $order->userService ? $order->userService->name : '未分配' }}</td>
            <td>{{ $order->created_at }}</td>
            <td>
                <a class="btn btn-primary btn-xs"
                   role="button"
                   data-toggle="collapse"
                   href=".collapseExample{{$order->id}}"
                   aria-expanded="true"
                   aria-controls="collapseExample">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
            </td>
        </tr>
        <tr class="collapse in collapseExample{{$order->id}} {{ $order->status_color }}">
            <td colspan="3">
                <address>
                    <strong>{{ $order->shipping_firstname . ' ' . $order->shipping_lastname }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city . ', ' . $order->shipping_state.' '.$order->shipping_zipcode }}<br>
                    <abbr title="ZipCode">Z:</abbr> {{ $order->shipping_zipcode }}
                    <abbr title="Phone">P:</abbr> {{ $order->shipping_phone }}
                    {{ $order->country ? $order->country->name.' '.$order->country->cn_name : '' }}
                </address>
                @if(count($order->refunds) > 0)
                    @foreach($order->refunds as $refund)
                        <div class="text-danger">
                            <label>退款ID:</label>{{ $refund->id }}
                            <label>退款金额:</label>{{ $refund->refund_amount }}
                            <label>原因:</label>{{ $refund->reason ? $refund->reason_name : '' }}
                            <label>申请时间:</label>{{ $refund->created_at }}
                        </div>
                    @endforeach
                @endif
            </td>
            <td colspan="25">
                <div class="col-lg-12 text-center">
                    @foreach($order->items as $orderItem)
                        <div class="row">
                            <div class="col-lg-2">
                                <img src="{{ asset($orderItem->item->product->dimage) }}" width="50px">
                            </div>
                            <div class="col-lg-2 text-primary">{{ $orderItem->sku .' [ '. $orderItem->channel_sku .' ]' }}</div>
                            @if($orderItem->item)
                                <div class="col-lg-2">
                                    <strong>{{ $orderItem->item->status_name }}</strong>
                                </div>
                                <div class="col-lg-4">{{ $orderItem->item->c_name }}</div>
                            @else
                                <div class="col-lg-2">
                                    <strong class="text-danger">未匹配</strong>
                                </div>
                                <div class="col-lg-2"></div>
                            @endif
                            <div class="col-lg-1">{{ $order->currency . ' ' . $orderItem->price }}</div>
                            <div class="col-lg-1">{{ 'X' . ' ' . $orderItem->quantity }}</div>
                        </div>
                    @endforeach
                    <div class="divider"></div>
                </div>
                <div class="row col-lg-12 text-center">
                    <div class="col-lg-3">物品数量: {{ $order->items->sum('quantity') }}</div>
                    <div class="col-lg-3">包裹个数: {{ $order->packages->count() }}</div>
                    <div class="col-lg-3">包裹总重: {{ $order->packages->sum('weight') }} Kg</div>
                    <div class="col-lg-3">运费合计: {{ $order->packages->sum('cost') }} RMB</div>
                </div>
            </td>
        </tr>
        <tr class="collapse in collapseExample{{$order->id}}">
            <td colspan="30" class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-3">
                            收款方式 : {{ $order->payment }}
                        </div>
                        <div class="col-lg-9">
                            交易号 : {{ $order->transaction_number }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-right">
                    @if($order->status == 'UNPAID' || $order->status == 'PAID' || $order->status == 'PREPARED' || $order->status == 'REVIEW' || $order->status == 'NEED')
                        <a href="{{ route('order.edit', ['id'=>$order->id]) }}" class="btn btn-danger btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> 编辑
                        </a>
                    @endif
                    @if($order->status == 'UNPAID' || $order->status == 'PAID' || $order->status == 'PREPARED' || $order->status == 'REVIEW')
                        <a href="{{ route('withdraw', ['id'=>$order->id]) }}" class="btn btn-danger btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> 撤单
                        </a>
                        <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                           data-id="{{ $order->id }}"
                           data-url="{{ route('order.destroy', ['id' =>$order->id]) }}">
                            <span class="glyphicon glyphicon-pencil"></span> 删除
                        </a>
                    @endif
                    <a href="{{ route('refund', ['id'=>$order->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 退款
                    </a>
                    <a href="{{ route('remark', ['id'=>$order->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 备注
                    </a>
                    @if($order->status == 'REVIEW')
                        <a href="javascript:" class="btn btn-primary btn-xs review" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 审核
                        </a>
                    @endif
                    @if($order->status == 'PREPARED' && $order->active != 'STOP')
                        <a href="javascript:" class="btn btn-primary btn-xs prepared" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 暂停发货
                        </a>
                    @endif
                    @if($order->active != 'NORMAL')
                        <a href="javascript:" class="btn btn-primary btn-xs normal" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 恢复正常
                        </a>
                    @endif
                    <a href="{{ route('order.show', ['id'=>$order->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 查看
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
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