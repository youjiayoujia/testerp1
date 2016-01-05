@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('item_id') }}">Item号{!! Sort::label('item_id') !!}</th>
    <th>sku</th>
    <th class="sort" data-url="{{ Sort::url('amount') }}">数量{!! Sort::label('amount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('total_amount') }}">总金额(￥){!! Sort::label('total_amount') !!}</th>
    <th>仓库</th>
    <th>库位</th>
    <th>入库类型</th>
    <th>入库来源</th>
    <th>备注</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stockin)
        <tr>
            <td>{{ $stockin->id }}</td>
            <td>{{ $stockin->item_id }}</td>
            <td>{{ $stockin->sku }}</td>
            <td>{{ $stockin->amount}}</td>
            <td>{{ $stockin->total_amount}}</td>
            <td>{{ $stockin->warehouse->name }}</td>
            <td>{{ $stockin->position->name }}</td>
            <td>{{ $stockin->type_name }}</td>
            <td>{{ $stockin->relation_id }}</td>
            <td>{{ $stockin->remark }} </td>
            <td>{{ $stockin->created_at }}</td>
            <td>
                <a href="{{ route('stockIn.show', ['id'=>$stockin->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('stockIn.edit', ['id'=>$stockin->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $stockin->id }}"
                   data-url="{{ route('stockIn.destroy', ['id' => $stockin->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
