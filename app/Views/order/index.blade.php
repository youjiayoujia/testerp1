@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="channel_id">渠道</th>
    <th class="sort" data-field="channel_account_id">渠道账号</th>
    <th class="sort" data-field="ordernum">订单号</th>
    <th class="sort" data-field="channel_ordernum">渠道订单号</th>
    <th>邮箱</th>
    <th>sku</th>
    <th>订单状态</th>
    <th>售后状态</th>
    <th class="sort" data-field="amount">总金额</th>
    <th class="sort" data-field="amount_product">产品金额</th>
    <th class="sort" data-field="amount_shipping">运费</th>
    <th class="sort" data-field="amount_coupon">折扣金额</th>
    <th>是否分批发货</th>
    <th>是否手工</th>
    <th>是否做账</th>
    <th>做账人员</th>
    <th>客服人员</th>
    <th>运营人员</th>
    <th>支付方式</th>
    <th>币种</th>
    <th class="sort" data-field="rate">汇率</th>
    <th>IP地址</th>
    <th>地址验证</th>
    {{--<th>备用字段</th>--}}
    <th>红人/choies用</th>
    <th>订单备注</th>
    <th>导单备注</th>
    <th>种类</th>
    <th>发货国家/地区</th>
    <th class="sort" data-field="payment_date">支付时间</th>
    <th class="sort" data-field="affair_time">做账时间</th>
    <th class="sort" data-field="create_time">渠道创建时间</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->channel->name }}</td>
            <td>{{ $order->channelAccount->alias }}</td>
            <td>{{ $order->ordernum }}</td>
            <td>{{ $order->channel_ordernum }}</td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->sku_name}}</td>
            <td>{{ $order->status_name }}</td>
            <td>{{ $order->active_name }}</td>
            <td>{{ $order->amount }}</td>
            <td>{{ $order->amount_product }}</td>
            <td>{{ $order->amount_shipping }}</td>
            <td>{{ $order->amount_coupon }}</td>
            <td>{{ $order->is_partial_name }}</td>
            <td>{{ $order->by_hand_name }}</td>
            <td>{{ $order->is_affair_name }}</td>
            <td>{{ $order->userAffairer ? $order->userAffairer->name : '' }}</td>
            <td>{{ $order->userService->name }}</td>
            <td>{{ $order->userOperator->name }}</td>
            <td>{{ $order->payment }}</td>
            <td>{{ $order->currency }}</td>
            <td>{{ $order->rate }}</td>
            <td>{{ $order->ip }}</td>
            <td>{{ $order->address_confirm_name }}</td>
            {{--<td>{{ $order->comment }}</td>--}}
            <td>{{ $order->comment1 }}</td>
            <td>{{ $order->remark }}</td>
            <td>{{ $order->import_remark }}</td>
            <td>{{ $order->shipping == 'PACKET' ? '小包' : '快递' }}</td>
            <td>{{ $order->shipping_country }}</td>
            <td>{{ $order->payment_date }}</td>
            <td>{{ $order->affair_time == '0000-00-00' ? '' : $order->affair_time }}</td>
            <td>{{ $order->create_time }}</td>
            <td>{{ $order->created_at }}</td>
            <td>{{ $order->updated_at }}</td>
            <td>
                <a href="{{ route('order.show', ['id'=>$order->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('order.edit', ['id'=>$order->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $order->id }}"
                   data-url="{{ route('order.destroy', ['id' =>$order->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
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
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'NEED']) }}">需补货</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'PACKED']) }}">打包完成</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'SHIPPED']) }}">发货完成</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'COMPLETE']) }}">订单完成</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'CANCEL']) }}">取消订单</a></li>
            <li><a href="{{ DataList::filtersEncode(['status', '=', 'ERROR']) }}">订单异常</a></li>
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
    @parent
@stop
