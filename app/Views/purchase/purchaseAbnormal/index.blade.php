@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选-
    ID</th>
    <th>sku</th>
    <th>采购类型</th>
    <th>订单itemId</th>
    <th>产品图片</th>
    <th>供应商</th>
    <th>采购去向</th>
    <th>采购需求/采购数目/仍需采购</th>
    <th>shop</th>
    <th>异常状态</th>
    <th>是否生成采购单</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseItem)
        <tr>
            <td>
             @if($purchaseItem->purchase_order_id >0)
                <input type="checkbox" name="purchaseItem_id"  value="{{$purchaseItem->id}}" isexamine="1" >
                @else
                <input type="checkbox" name="purchaseItem_id"  value="{{$purchaseItem->id}}" isexamine="0" >
                @endif
            {{ $purchaseItem->id }}</td>
            <td>{{ $purchaseItem->sku}}</td>
            @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($purchaseItem->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
            <td>{{ $purchaseItem->order_item_id}}</td>
            <td> <img src="{{ $purchaseItem->item->product->image->src}}" height="50px"/></td>
            <td>{{ $purchaseItem->supplier->name}}</td>
            <td>{{ $purchaseItem->warehouse->name}}</td>
            <td>{{ $purchaseItem->purchase_num}}/{{ $purchaseItem->arrival_num}}/{{ $purchaseItem->lack_num}}</td>
             @foreach(config('purchase.purchaseItem.channels') as $k=>$channel)
            	@if($purchaseItem->platform_id == $k)
            	<td>{{ $channel }}</td>
                @endif
            @endforeach
            <td>
            @foreach(config('purchase.purchaseItem.active') as $k=>$status)
            	@if($purchaseItem->active == $k)
            	{{ $status }}
                @endif
            @endforeach
             @if($purchaseItem->active == 1)
            @foreach(config('purchase.purchaseItem.active_status.1') as $key=>$v)
           	{{$purchaseItem->active_status == $key ? $v : ''}} 
            @endforeach
            @elseif($purchaseItem->active == 2)
            @foreach(config('purchase.purchaseItem.active_status.2') as $key=>$v)
            {{$purchaseItem->active_status == $key ? $v : ''}} 
            @endforeach
            @elseif($purchaseItem->active == 3)
            @foreach(config('purchase.purchaseItem.active_status.3') as $key=>$v)
            {{$purchaseItem->active_status == $key ? $v : ''}} 
            @endforeach
            @elseif($purchaseItem->active == 4)
            @foreach(config('purchase.purchaseItem.active_status.4') as $key=>$v)
            {{$purchaseItem->active_status == $key ? $v : ''}} 
            @endforeach
            @endif
            </td>
            	@if($purchaseItem->purchase_order_id)
            	<td>已生成采购单</td>
                @else
                <td>未生成采购单</td>
                @endif           
            <td>{{ $purchaseItem->created_at }}</td>
            <td>
                 
                <a href="{{ route('purchaseAbnormal.edit', ['id'=>$purchaseItem->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 处理异常
                </a>
                
            </td>
        </tr>
    @endforeach
  
@stop