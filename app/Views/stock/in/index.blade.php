@extends('common.table')
@section('tableToolButtons')@stop
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>sku</th>
    <th class='sort' data-field='amount'>数量</th>
    <th class='sort' data-field='total_amount'>总金额(￥)</th>
    <th>仓库</th>
    <th>库位</th>
    <th>入库类型</th>
    <th>入库来源</th>
    <th>备注</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stockin)
        <tr>
            <td>{{ $stockin->id }}</td>
            <td>{{ $stockin->stock ? $stockin->stock->items ? $stockin->stock->items->sku : '' : '' }}</td>
            <td>{{ $stockin->quantity}}</td>
            <td>{{ $stockin->amount}}</td>
            <td>{{ $stockin->stock ? $stockin->stock->warehouse ? $stockin->stock->warehouse->name : '' : '' }}</td>
            <td>{{ $stockin->stock ? $stockin->stock->position ? $stockin->stock->position->name : '' : '' }}</td>
            <td>{{ $stockin->type_name }}</td>
            <td>{{ $stockin->relation_name }}</td>
            <td>{{ $stockin->remark }} </td>
            <td>{{ $stockin->created_at }}</td>
            <td>
                <a href="{{ route('stockIn.show', ['id'=>$stockin->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
            </td>
        </tr>
    @endforeach
@stop
