@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('item_id') }}">Item号{!! Sort::label('item_id') !!}</th>
    <th>sku</th>
    <th class="sort" data-url="{{ Sort::url('amount') }}">数量{!! Sort::label('amount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('total_amount') }}">总金额{!! Sort::label('total_amount') !!}</th>
    <th>仓库</th>
    <th>库位</th>
    <th>出库类型</th>
    <th>出库来源</th>
    <th>备注</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stockout)
        <tr>
            <td>{{ $stockout->id }}</td>
            <td>{{ $stockout->item_id }}</td>
            <td>{{ $stockout->sku }}</td>
            <td>{{ $stockout->amount}}</td>
            <td>{{ $stockout->total_amount}}</td>
            <td>{{ $stockout->warehouse->name }}</td>
            <td>{{ $stockout->position->name }}</td>
            <td>{{ $stockout->type_name }}</td>
            <td>{{ $stockout->relation_id }}</td>
            <td>{{ $stockout->remark }} </td>
            <td>{{ $stockout->created_at }}</td>
            <td>
                <a href="{{ route('stockOut.show', ['id'=>$stockout->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('stockOut.edit', ['id'=>$stockout->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $stockout->id }}"
                   data-url="{{ route('stockOut.destroy', ['id' => $stockout->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
