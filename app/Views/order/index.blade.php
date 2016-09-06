@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuanOrder()">全选</th>
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="ordernum">订单号</th>
    <th class="sort" data-field="channel_ordernum">渠道订单号</th>
    <th class="sort" data-field="channel">渠道</th>
    <th class="sort" data-field="channel_account_id">渠道账号</th>
    <th>邮箱</th>
    <th>买家ID</th>
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
        <tr class="dark-{{ $order->status_color }}">
            <td>
                <input type="checkbox" name="tribute_id" value="{{$order->id}}">
            </td>
            <td>{{ $order->id }}</td>
            <td>{{ $order->ordernum }}</td>
            <td>
                {{ $order->channel_ordernum }}
                @if($order->fulfill_by == 'AFN')
                    <span class="label label-danger">亚马逊配送</span>
                @endif
            </td>
            <td>{{ $order->channel ? $order->channel->name : '' }}</td>
            <td>{{ $order->channelAccount ? $order->channelAccount->alias : '' }}</td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->by_id }}</td>
            <td>{{ $order->shipping }}</td>
            <td>{{ $order->shipping_firstname . ' ' . $order->shipping_lastname }}</td>
            <td>{{ $order->shipping_country }}</td>
            <td>{{ $order->currency . ' ' . $order->amount }}</td>
            <td><strong class="text-danger">{{ $order->currency . ' ' . $order->amount_shipping }}</strong></td>
            <td>
                {{--@if($order->status == 'PACKED')--}}
                    {{--<div>{{ $order->calculateProfitProcess() }}</div>--}}
                    {{--<div>产品成本: {{ $order->all_item_cost }} RMB</div>--}}
                    {{--<div>运费成本: {{ $order->packages->sum('cost') }} RMB</div>--}}
                    {{--<div>平台费: {{ '' }}USD</div>--}}
                    {{--<div>毛利润: {{ '' }}USD</div>--}}
                {{--@else--}}
                {{--@endif--}}
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
                    {{ $order->country ? $order->country->name.' '.$order->country->cn_name : '' }}<br>
                    <abbr title="ZipCode">Z:</abbr> {{ $order->shipping_zipcode }}
                    <abbr title="Phone">P:</abbr> {{ $order->shipping_phone }}
                </address>
                @if($order->customer_remark)
                    <div class="divider"></div>
                    <div class="text-danger">
                        {{ $order->customer_remark }}
                    </div>
                @endif
                @if($order->remarks)
                    @foreach($order->remarks as $remark)
                        <div class="divider"></div>
                        <div class="text-danger">
                            {{ $remark->remark }}
                        </div>
                    @endforeach
                @endif
                @if(count($order->refunds) > 0)
                    @foreach($order->refunds as $refund)
                        <div class="divider"></div>
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
                            <div class="col-lg-1">
                                ID:{{ $orderItem->item ? $orderItem->item->product_id : '' }}
                                <br>
                                @if($order->channel)
                                    @if($order->channel->driver == 'ebay')
                                        ebay站点: {{ $order->shipping_country }}
                                    @endif
                                @endif
                            </div>
                            {{--<div class="col-lg-1">{{ $orderItem->id . '@' . $orderItem->sku }}</div>--}}
                            @if($orderItem->item)
                                <div class="col-lg-2">
                                    <img src="{{ asset($orderItem->item->product->dimage) }}" width="50px">
                                </div>
                            @else
                                <div class="col-lg-2">
                                    <img src="{{ asset('default.jpg') }}" width="50px">
                                </div>
                            @endif
                            <div class="col-lg-2 text-primary">{{ $orderItem->sku .' [ '. $orderItem->channel_sku .' ]' }}</div>
                            @if($orderItem->item)
                                <div class="col-lg-2">
                                    <strong>{{ $orderItem->item->status_name }}</strong>
                                </div>
                                <div class="col-lg-3">{{ $orderItem->item->c_name }}</div>
                            @else
                                <div class="col-lg-2">
                                    <strong class="text-danger">未匹配</strong>
                                </div>
                                <div class="col-lg-2"></div>
                            @endif
                            <div class="col-lg-1">{{ $order->currency . ' ' . $orderItem->price }}</div>
                            <div class="col-lg-1">{{ 'X' . ' ' . $orderItem->quantity }}</div>
                        </div>
                        <div class="divider"></div>
                    @endforeach
                </div>
                <div class="row col-lg-12 text-center">
                    <div class="col-lg-3">物品数量: {{ $order->items->sum('quantity') }}</div>
                    <div class="col-lg-3">包裹个数: {{ $order->packages->count() }}</div>
                    <div class="col-lg-3">包裹总重: {{ $order->packages->sum('weight') }} Kg</div>
                    <div class="col-lg-3">运费合计: {{ $order->packages->sum('cost') }} RMB</div>
                </div>
            </td>
        </tr>
        <tr class="collapse in collapseExample{{$order->id}} {{ $order->status_color }}">
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
                        <button class="btn btn-danger btn-xs"
                                data-toggle="modal"
                                data-target="#withdraw{{ $order->id }}"
                                title="撤单">
                            <span class="glyphicon glyphicon-link"></span> 撤单
                        </button>
                        <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                           data-id="{{ $order->id }}"
                           data-url="{{ route('order.destroy', ['id' =>$order->id]) }}">
                            <span class="glyphicon glyphicon-pencil"></span> 删除
                        </a>
                    @endif
                    @foreach($order->items as $item)
                        @if($item->is_refund == 0)
                            <button class="btn btn-primary btn-xs"
                                    data-toggle="modal"
                                    data-target="#refund{{ $order->id }}"
                                    title="退款">
                                <span class="glyphicon glyphicon-link"></span> 退款
                            </button>
                            <?php break ?>
                        @endif
                    @endforeach
                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#send_ebay_message_{{ $order->id }}" title="Send ebay Message">
                        <span class="glyphicon glyphicon-envelope"></span> Send ebay Message
                    </button>
                    <button class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#remark{{ $order->id }}"
                            title="备注">
                        <span class="glyphicon glyphicon-link"></span> 备注
                    </button>
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
                    @if(count($order->packages) > 0)
                        <button class="btn btn-primary btn-xs"
                                data-toggle="modal"
                                data-target="#package{{ $order->id }}"
                                title="包裹">
                            <span class="glyphicon glyphicon-link"></span> 包裹
                        </button>
                    @endif
                    @if($order->status == 'CANCEL')
                        <a href="javascript:" class="btn btn-primary btn-xs recover" data-id="{{ $order->id }}">
                            <span class="glyphicon glyphicon-pencil"></span> 恢复订单
                        </a>
                    @endif
                    <a href="{{ route('invoice', ['id'=>$order->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 德国发票
                    </a>
                    <a href="{{ route('order.show', ['id'=>$order->id]) }}" class="btn btn-primary btn-xs">
                        <span class="glyphicon glyphicon-eye-open"></span> 查看
                    </a>
                </div>
            </td>
        </tr>
        <div class="modal fade" id="withdraw{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('withdrawUpdate', ['id' => $order->id])}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">撤单</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="withdraw" class='control-label'>撤单原因</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control" name="withdraw" id="withdraw">
                                        <option value="NULL">==选择原因==</option>
                                        @foreach(config('order.withdraw') as $withdraw_key => $withdraw)
                                            <option value="{{ $withdraw_key }}" {{ old('withdraw') == $withdraw_key ? 'selected' : '' }}>
                                                {{ $withdraw }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="withdraw_reason" class='control-label'>原因</label>
                                    <textarea class="form-control" rows="3" name='withdraw_reason' id="withdraw_reason">{{ old('withdraw_reason') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="refund{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('refundUpdate', ['id' => $order->id])}}" method="POST" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">退款信息</h4>
                        </div>
                        <div class="modal-body">
                            <label class='control-label'>历史退款</label>
                            @if($order->refunds->toArray())
                                <div class='row'>
                                    <div class="form-group col-sm-2">
                                        <label for="id" class='control-label'>退款ID</label>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="refund_amount" class='control-label'>退款金额</label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="reason" class='control-label'>退款原因</label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="created_at" class='control-label'>申请时间</label>
                                    </div>
                                </div>
                                @foreach($order->refunds as $refund)
                                    <div class="row text-danger">
                                        <div class="col-lg-2">{{ $refund->id }}</div>
                                        <div class="col-lg-2">{{ $refund->refund_amount }}</div>
                                        <div class="col-lg-4">{{ $refund->reason ? $refund->reason_name : '' }}</div>
                                        <div class="col-lg-4">{{ $refund->created_at }}</div>
                                    </div>
                                    <div class="divider"></div>
                                @endforeach
                            @else
                                <div class="divider"></div>
                            @endif
                            <div class='row'>
                                <div class="form-group col-lg-4">
                                    <label for="ordernum" class='control-label'>订单号</label>
                                    <label class="text-danger">(已付款时长: {{ '' }} 天)</label>
                                    <input class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') ? old('ordernum') : $order->ordernum }}" readonly>
                                </div>
                                <div class="form-group col-lg-2">
                                    <label for="channel_account_id">渠道账号</label>
                                    <input class="form-control" id="channel_account_id" placeholder="渠道账号" name='channel_account_id' value="{{ old('channel_account_id') ? old('channel_account_id') : $order->channelAccount->alias }}" readonly>
                                </div>
                                {{--<div class="form-group col-lg-2" id="payment">--}}
                                    {{--<label for="payment_date" class='control-label'>支付时间</label>--}}
                                    {{--<small class="text-danger glyphicon glyphicon-asterisk"></small>--}}
                                    {{--<input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') }}">--}}
                                {{--</div>--}}
                                <div class="form-group col-lg-2">
                                    <label for="refund_amount" class='control-label'>退款金额</label>
                                    <input class="form-control" id="refund_amount{{ $order->id }}" placeholder="退款金额" name='refund_amount' value="{{ old('refund_amount') }}">
                                </div>
                                <div class="form-group col-lg-2">
                                    <label for="price" class='control-label'>确认金额</label>
                                    <input class="form-control" id="price{{ $order->id }}" placeholder="确认金额" name='price' value="{{ old('price') }}">
                                </div>
                                <div class="form-group col-lg-2">
                                    <label for="refund_currency" class='control-label'>退款币种</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control" name="refund_currency" id="refund_currency">
                                        @foreach($currencys as $refund_currency)
                                            <option value="{{ $refund_currency->code }}" {{ old('refund_currency') }}>
                                                {{ $refund_currency->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="refund" class='control-label'>退款方式</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control" name="refund" id="refund">
                                        <option value="NULL">==退款方式==</option>
                                        @foreach(config('order.refund') as $refund_key => $refund)
                                            <option value="{{ $refund_key }}" {{ old('refund') }}>
                                                {{ $refund }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="reason" class='control-label'>退款原因</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control" name="reason" id="reason">
                                        <option value="NULL">==退款原因==</option>
                                        @foreach(config('order.reason') as $reason_key => $reason)
                                            <option value="{{ $reason_key }}" {{ old('reason') }}>
                                                {{ $reason }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="type" class='control-label'>退款类型</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control type" name="type" id="{{ $order->id }}">
                                        <option value="NULL">==退款类型==</option>
                                        @foreach(config('order.type') as $type_key => $type)
                                            <option value="{{ $type_key }}" {{ old('type') }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($order->items->toArray())
                                <div class='row'>
                                    <div class="form-group col-sm-2">
                                        <input type="checkbox" isCheck="true" id="checkall{{ $order->id }}" placeholder="" onclick="quanxuan('{{ $order->id }}')">全选
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="id" class='control-label'>ID</label>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="sku" class='control-label'>sku</label>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="price" class='control-label'>单价</label>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="quantity" class='control-label'>数量</label>
                                    </div>
                                </div>
                                @foreach($order->items as $key => $orderItem)
                                    @if($orderItem->is_refund == 0)
                                        <div class='row'>
                                            <div class="form-group col-sm-2">
                                                <input type="checkbox" name="tribute_id[]" value="{{$orderItem->id}}">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <input type='text' class="id" id="arr[id][{{$key}}]" style="border: 0" placeholder="id" name='arr[id][{{$key}}]' value="{{ old('arr[id][$key]') ? old('arr[id][$key]') : $orderItem->id }}" readonly>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <input type='text' class="sku" id="arr[sku][{{$key}}]" style="border: 0" placeholder="sku" name='arr[sku][{{$key}}]' value="{{ old('arr[sku][$key]') ? old('arr[sku][$key]') : $orderItem->sku }}" readonly>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <input type='text' class="form-control price" id="arr[price][{{$key}}]" placeholder="单价" name='arr[price][{{$key}}]' value="{{ old('arr[price][$key]') ? old('arr[price][$key]') : $orderItem->price }}" readonly>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <input type='text' class="form-control quantity" id="arr[quantity][{{$key}}]" placeholder="数量" name='arr[quantity][{{$key}}]' value="{{ old('arr[quantity][$key]') ? old('arr[quantity][$key]') : $orderItem->quantity }}" readonly>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="memo" class='control-label'>Memo(只能填写英文)</label>
                                    <label class="text-danger">发给客户看的</label>
                                    <input class="form-control" id="memo" placeholder="" name='memo' value="{{ old('memo') }}">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label for="detail_reason" class='control-label'>详细原因</label>
                                    <label class="text-danger">挂号的,必须填写查询结果</label>
                                    <textarea class="form-control" rows="3" name="detail_reason" id="detail_reason">{{ old('detail_reason') }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="image">上传截图：</label>
                                    <label class="text-danger">(图片最大支持上传40Kb)</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <input name='image' type='file'/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="remark{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('remarkUpdate', ['id' => $order->id])}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">补充备注</h4>
                        </div>
                        <div class="modal-body">
                            <label class='control-label'>历史备注</label>
                            @if($order->remarks->toArray())
                                @foreach($order->remarks as $remark)
                                    <div class="row">
                                        <div class="col-lg-2">{{ $remark->user?$remark->user->name:'系统创建' }}</div>
                                        <div class="col-lg-4">{{ $remark->created_at }}</div>
                                        <div class="col-lg-6">{{ $remark->remark }}</div>
                                    </div>
                                    <div class="divider"></div>
                                @endforeach
                            @else
                                <div class="divider"></div>
                            @endif
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="remark" class='control-label'>订单备注</label>
                                    <textarea class="form-control" rows="3" id="remark" name='remark'>{{ old('remark') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="package{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">包裹信息</h4>
                    </div>
                    <div class="modal-body">
                        @if($order->packages->toArray())
                            @foreach($order->packages as $package)
                                <div class="row">
                                    <div class="col-lg-3">
                                        <strong>包裹ID</strong> : <a href="{{ route('package.show', ['id'=>$package->id]) }}">{{ $package->id }}</a>
                                    </div>
                                    <div class="col-lg-3">
                                        <strong>物流方式</strong> : {{ $package->logistics ? $package->logistics->logistics_type : '' }}
                                    </div>
                                    <div class="col-lg-3">
                                        <strong>追踪号</strong> : <a href="http://{{ $package->tracking_link }}">{{ $package->tracking_no }}</a>
                                    </div>
                                    <div class="col-lg-3">
                                        <strong>包裹状态</strong> : {{ $package->status }}
                                    </div>
                                    <div class="col-lg-3">
                                        <strong>打印面单时间</strong> : {{ $package->printed_at }}
                                    </div>
                                    <div class="col-lg-3">
                                        <strong>发货时间</strong> : {{ $package->shipped_at }}
                                    </div>
                                    <div class="col-lg-3">
                                        <strong>交付时间</strong> : {{ $package->delivered_at }}
                                    </div>
                                    <div class="col-lg-3">
                                        <strong>妥投时效</strong> : {{ ($package->shipped_at) - ($package->delivered_at) }}
                                    </div>
                                    <div class="col-lg-3">
                                        <strong>备注</strong> : {{ $package->remark }}
                                    </div>
                                </div>
                                <div class="divider"></div>
                            @endforeach
                        @else
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="send_ebay_message_{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('withdrawUpdate', ['id' => $order->id])}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">撤单</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="withdraw" class='control-label'>撤单原因</label>
                                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                                    <select class="form-control" name="withdraw" id="withdraw">
                                        <option value="NULL">==选择原因==</option>
                                        @foreach(config('order.withdraw') as $withdraw_key => $withdraw)
                                            <option value="{{ $withdraw_key }}" {{ old('withdraw') == $withdraw_key ? 'selected' : '' }}>
                                                {{ $withdraw }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="withdraw_reason" class='control-label'>原因</label>
                                    <textarea class="form-control" rows="3" name='withdraw_reason' id="withdraw_reason">{{ old('withdraw_reason') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <input class="form-control lr" id="lr" placeholder="利润" name="lr">
    </div>
    <div class="btn-group" role="group">
        <select class="form-control sx" name="sx" id="sx">
            <option value="null">利润筛选</option>
            <option value="high">高于</option>
            <option value="low">低于</option>
        </select>
    </div>
    <div class="btn-group" role="group">
        <select class="form-control special" name="special" id="special">
            <option value="null">特殊要求</option>
            <option value="yes">有特殊要求</option>
        </select>
    </div>
    <div class="btn-group">
        <button class="btn btn-info"
                data-toggle="modal"
                data-target="#withdraw"
                title="SMT批量撤单">
            <span class="glyphicon glyphicon-link"></span> SMT批量撤单
        </button>
    </div>
    <div class="modal fade" id="withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">SMT批量撤单</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group text-left col-lg-6">
                            <label for="withdraw" class='control-label'>撤单原因</label>
                            <small class="text-danger glyphicon glyphicon-asterisk"></small>
                            <select class="form-control withdraw" name="withdraw" id="withdraw">
                                <option value="NULL">==选择原因==</option>
                                @foreach(config('order.withdraw') as $withdraw_key => $withdraw)
                                    <option value="{{ $withdraw_key }}" {{ old('withdraw') == $withdraw_key ? 'selected' : '' }}>
                                        {{ $withdraw }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group text-left col-lg-6">
                            <label for="withdraw_reason" class='control-label'>原因</label>
                            <textarea class="form-control" rows="3" name='withdraw_reason' id="withdraw_reason">{{ old('withdraw_reason') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary sub">提交</button>
                </div>
            </div>
        </div>
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

            $('.special').change(function () {
                var special = $('.special').val();
                if (special != null) {
                    location.href = "{{ route('order.index') }}?special=" + special;
                }
            });

            $('.sx').change(function () {
                var lr = $('.lr').val();
                if (lr == '') {
                    alert('请输入利润!');
                    $('.sx').val('null');
                }else {
                    var sx = $('.sx').val();
                    if (sx != null) {
                        location.href = "{{ route('order.index') }}?sx=" + sx +"&lr=" + lr;
                    }
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

            //恢复订单
            $('.recover').click(function () {
                if (confirm("确认恢复订单?")) {
                    var order_id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('updateRecover') }}",
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

        //全选订单产品
        function quanxuan(id)
        {
            var collid = document.getElementById("checkall"+id);
            var coll = document.getElementsByName("tribute_id[]");
            if (collid.checked){
                for(var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
                $('.price').style.readonly = 'false';
            }else{
                for(var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }

        $('.type').click(function() {
            var type = $(this).val();
            var id = $(this).attr('id');
            if (type == 'FULL') {
                document.getElementById('price'+id).readOnly = true;
                document.getElementById('refund_amount'+id).readOnly = true;
            } else {
                document.getElementById('price'+id).readOnly = false;
                document.getElementById('refund_amount'+id).readOnly = false;
            }
        });

        //SMT批量撤单
        $('.sub').click(function () {
            if (confirm('确认提交?')) {
                var checkbox = document.getElementsByName("tribute_id");
                var order_ids = "";
                var withdraw = $('.withdraw').val();
                var withdraw_reason = $('#withdraw_reason').val();
                for (var i = 0; i < checkbox.length; i++) {
                    if(!checkbox[i].checked)
                        continue;
                    order_ids += checkbox[i].value+",";
                }
                order_ids = order_ids.substr(0,(order_ids.length)-1);
                $.ajax({
                    url : "{{ route('withdrawAll') }}",
                    data : {order_ids : order_ids, withdraw : withdraw, withdraw_reason : withdraw_reason},
                    dataType : 'json',
                    type : 'get',
                    success:function(result){
                        window.location.reload();
                    }
                });
            }
        });

        //全选订单
        function quanxuanOrder()
        {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id");
            if (collid.checked){
                for(var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            }else{
                for(var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }
    </script>
@stop