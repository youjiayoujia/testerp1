@extends('common.table')
@section('tableHeader')
    <th>ID</th>
    <th>Item_ID</th>
    <th>采购类型</th>
    <th>订单id</th>
    <th>产品图片</th>
    <th>供应商</th>
    <th>采购去向</th>
    <th>库存数量</th>
    <th>采购需求/采购数目/仍需采购</th>
    <th>shop</th>
    <th>采购条目状态</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseOrder)
        <tr>
            <td>{{ $purchaseOrder->id }}</td>
            <td>{{ $purchaseOrder->sku_id}}</td>
            @foreach(config('purchase.purchaseOrder.type') as $k=>$type)
            	@if($purchaseOrder->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
            <td>{{ $purchaseOrder->order_id}}</td>
            <td><img src="{{ asset($purchaseOrder->purchaseOrderImage->product->image->src)}}" height="50px"/></td>
            <td>{{ $purchaseOrder->supplier->name}}</td>
            <td>{{ $purchaseOrder->warehouse->name}}</td>
            <td>{{ $purchaseOrder->stock}}</td>
            <td>{{ $purchaseOrder->purchase_num}}/{{ $purchaseOrder->arrival_num}}/{{ $purchaseOrder->lack_num}}</td>
             @foreach(config('purchase.purchaseOrder.platforms') as $k=>$platform)
            	@if($purchaseOrder->platform_id == $k)
            	<td>{{ $platform }}</td>
                @endif
            @endforeach
            @foreach(config('purchase.purchaseOrder.status') as $k=>$status)
            	@if($purchaseOrder->status == $k)
            	<td>{{ $status }}</td>
                @endif
            @endforeach            
            <td>{{ $purchaseOrder->created_at }}</td>
            <td>
                <a href="{{ route('purchaseOrder.show', ['id'=>$purchaseOrder->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $purchaseOrder->id }}"
                   data-url="{{ route('purchaseOrder.destroy', ['id' =>$purchaseOrder->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop