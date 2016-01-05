@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('item_id') }}">Item号{!! Sort::label('item_id') !!}</th>
    <th>sku</th>  
    <th>仓库</th>
    <th>库位</th>
    <th class="sort" data-url="{{ Sort::url('all_amount') }}">总数量{!! Sort::label('all_amount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('available_amount') }}">可用数量{!! Sort::label('available_amount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('hold_amount') }}">hold数量{!! Sort::label('hold_amount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('total_amount') }}">总金额(￥){!! Sort::label('total_amount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $stock)
        <tr>
            <td>{{ $stock->id }}</td>
            <td>{{ $stock->item_id }}</td>
            <td>{{ $stock->sku }}</td>
            <td>{{ $stock->warehouse->name }}</td>
            <td>{{ $stock->position->name }}</td>
            <td>{{ $stock->all_amount}}</td>
            <td>{{ $stock->available_amount}}</td>
            <td>{{ $stock->hold_amount}}</td>
            <td>{{ $stock->total_amount}}</td>
            <td>{{ $stock->created_at }}</td>
            <td>
                <a href="{{ route('stock.show', ['id'=>$stock->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('stock.edit', ['id'=>$stock->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
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
