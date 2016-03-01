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
    @foreach($data as $purchaseItem)
        <tr>
            <td>{{ $purchaseItem->id }}</td>
            <td>{{ $purchaseItem->sku_id}}</td>
            @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($purchaseItem->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
            <td>{{ $purchaseItem->order_id}}</td>
            <td><img src="{{ asset($purchaseItem->purchaseItemImage->product->image->src)}}" height="50px"/></td>
            <td>{{ $purchaseItem->supplier->name}}</td>
            <td>{{ $purchaseItem->warehouse->name}}</td>
            <td>{{ $purchaseItem->stock}}</td>
            <td>{{ $purchaseItem->purchase_num}}/{{ $purchaseItem->arrival_num}}/{{ $purchaseItem->lack_num}}</td>
             @foreach(config('purchase.purchaseItem.platforms') as $k=>$platform)
            	@if($purchaseItem->platform_id == $k)
            	<td>{{ $platform }}</td>
                @endif
            @endforeach
            @foreach(config('purchase.purchaseItem.status') as $k=>$status)
            	@if($purchaseItem->status == $k)
            	<td>{{ $status }}</td>
                @endif
            @endforeach            
            <td>{{ $purchaseItem->created_at }}</td>
            <td>
                <a href="{{ route('purchaseItem.show', ['id'=>$purchaseItem->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('purchaseItem.edit', ['id'=>$purchaseItem->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $purchaseItem->id }}"
                   data-url="{{ route('purchaseItem.destroy', ['id' =>$purchaseItem->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop