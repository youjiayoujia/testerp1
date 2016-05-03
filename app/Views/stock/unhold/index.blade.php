@extends('common.table')
@section('tableToolButtons')@stop
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>sku</th>
    <th class='sort' data-field='amount'>数量</th>
    <th>仓库</th>
    <th>库位</th>
    <th>入库类型</th>
    <th>入库来源</th>
    <th>备注</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stockUnhold)
        <tr>
            <td>{{ $stockUnhold->id }}</td>
            <td>{{ $stockUnhold->stock ? $stockUnhold->stock->items ? $stockUnhold->stock->items->sku : '' : '' }}</td>
            <td>{{ $stockUnhold->quantity}}</td>
            <td>{{ $stockUnhold->stock ? $stockUnhold->stock->warehouse ? $stockUnhold->stock->warehouse->name : '' : '' }}</td>
            <td>{{ $stockUnhold->stock ? $stockUnhold->stock->position ? $stockUnhold->stock->position->name : '' : '' }}</td>
            <td>{{ $stockUnhold->type_name }}</td>
            <td>{{ $stockUnhold->relation_name }}</td>
            <td>{{ $stockUnhold->remark }} </td>
            <td>{{ $stockUnhold->created_at }}</td>
            <td>
                <a href="{{ route('stockUnhold.show', ['id'=>$stockUnhold->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
            </td>
        </tr>
    @endforeach
@stop
