@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>sku</th>  
    <th>仓库</th>
    <th>库位</th>
    <th class='sort' data-field='all_quantity'>总数量</th>
    <th class='sort' data-field='available_quantity'>可用数量</th>
    <th class='sort' data-field='hold_quantity'>hold数量</th>
    <th class='sort' data-field='unit_cost'>单价</th>
    <th class='sort' data-field='amount'>总金额</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stock)
        <tr>
            <td>{{ $stock->id }}</td>
            <td>{{ $stock->items ? $stock->items->sku : '' }}</td>
            <td>{{ $stock->warehouse ? $stock->warehouse->name : '' }}</td>
            <td>{{ $stock->position ? $stock->position->name : '' }}</td>
            <td>{{ $stock->all_quantity}}</td>
            <td>{{ $stock->available_quantity}}</td>
            <td>{{ $stock->hold_quantity}}</td>
            <td>{{ $stock->unit_cost }}</td>
            <td>{{ round($stock->all_quantity * $stock->unit_cost, 3) }}</td>
            <td>{{ $stock->created_at }}</td>
            <td>
                <a href="{{ route('stock.show', ['id'=>$stock->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $stock->id }}"
                   data-url="{{ route('stock.destroy', ['id' => $stock->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
@parent
<div class="btn-group">
    <a class="btn btn-info" href="{{ route('stock.getExcel') }}">
        获取excel
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('stock.importByExcel') }}">
        excel导入
    </a>
</div>
@stop
