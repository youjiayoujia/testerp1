@extends('common.table')
@section('tableToolButtons')@stop
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>sku</th>
    <th class='sort' data-field='amount'>数量</th>
    <th class='sort' data-field='total_amount'>总金额</th>
    <th>仓库</th>
    <th>库位</th>
    <th>出库类型</th>
    <th>出库来源</th>
    <th>备注</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stockout)
        <tr>
            <td>{{ $stockout->id }}</td>
            <td>{{ $stockout->stock ? $stockout->stock->items ? $stockout->stock->items->sku : '' : '' }}</td>
            <td>{{ $stockout->quantity}}</td>
            <td>{{ $stockout->amount}}</td>
            <td>{{ $stockout->stock ? $stockout->stock->warehouse ? $stockout->stock->warehouse->name : '' : '' }}</td>
            <td>{{ $stockout->stock ? $stockout->stock->position ? $stockout->stock->position->name : '' : '' }}</td>
            <td>{{ $stockout->type_name }}</td>
            <td>{{ $stockout->relation_name }}</td>
            <td>{{ $stockout->remark }} </td>
            <td>{{ $stockout->created_at }}</td>
            <td>
                <a href="{{ route('stockOut.show', ['id'=>$stockout->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
            </td>
        </tr>
    @endforeach
@stop
