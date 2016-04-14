@extends('common.table')
@section('tableToolButtons')

@stop{{-- 工具按钮 --}}
@section('tableHeader')
	
    <th>ID</th>
    <th>Item_ID</th>
    <th>采购类型</th>
    <th>产品图片</th>
    <th>供应商-采购链接</th>
    <th>采购去向</th>
    <th>采购需求/采购数目/仍需采购</th>
    <th>shop</th>
    <th>采购条目状态</th>
    <th>入库状态</th>
    <th>异常状态</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseList)
        <tr>
        
        @if($purchaseList->purchase_order_id > 0)
            <td>{{ $purchaseList->id }}</td>
            <td>{{ $purchaseList->sku}}</td>
            @foreach(config('purchase.purchaseItem.type') as $k=>$type)
            	@if($purchaseList->type == $k)
            	<td>{{ $type }}</td>
                @endif
            @endforeach
           
            <td><img src="{{ asset($purchaseList->item->product->image->src)}}" height="50px"/></td>
            <td>{{ $purchaseList->supplier->name}}
            @if($purchaseList->supplier->type ==1)
            <a href="http://{{$purchaseList->supplier->url}}">{{$purchaseList->supplier->url}}</a>
            @else
            线下采购
            @endif
            </td>
            <td>{{ $purchaseList->warehouse->name}}</td>
            <td>{{ $purchaseList->purchase_num}}/{{ $purchaseList->arrival_num}}/{{ $purchaseList->lack_num}}</td>
             @foreach(config('purchase.purchaseItem.channels') as $k=>$platform)
            	@if($purchaseList->platform_id == $k)
            	<td>{{ $platform }}</td>
                @endif
            @endforeach
            @foreach(config('purchase.purchaseItem.status') as $k=>$status)
            	@if($purchaseList->status == $k)
            	<td>{{ $status }}</td>
                @endif
            @endforeach
                      
            <td>@foreach(config('purchase.purchaseItem.storageStatus') as $k=>$vo)
            	@if($purchaseList->storageStatus == $k)  
            	{{ $vo }}
                
                @endif
            @endforeach</td>
            <td> 
           @foreach(config('purchase.purchaseItem.active') as $k=>$vo)
            	@if($purchaseList->active == $k)  
            	{{ $vo }}
                @if($k >0)- @endif
                @endif
            @endforeach
            @if($purchaseList->active==1)
            @foreach(config('purchase.purchaseItem.active_status.1') as $key => $v)
                    @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==2)
            @foreach(config('purchase.purchaseItem.active_status.2') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==3)
            @foreach(config('purchase.purchaseItem.active_status.3') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @elseif($purchaseList->active==4)
            @foreach(config('purchase.purchaseItem.active_status.4') as $key => $v)
                   @if($purchaseList->active_status==$key) {{$v}} @endif 
            @endforeach
            @endif
            </td>
            <td>
                <a href="{{ route('purchaseItemList.show', ['id'=>$purchaseList->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>       
               
                 
                <a href="{{ route('purchaseItemList.edit', ['id'=>$purchaseList->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
            </td>
            @endif
        </tr>
    @endforeach
@stop
 