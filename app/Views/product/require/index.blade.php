@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>选款名</th>
    <th>省</th>
    <th>市</th>
    <th>类似款sku</th>
    <th>竞争产品url</th>
    <th>选款备注</th>
    <th class='sort' data-field='expected_date'>期待上传时间</th>
    <th>需求人</th>
    <th>需求店铺</th>
    <th>创建人</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>处理状态</th>
    <th>处理者id</th>
    <th class='sort' data-field='handle_time'>处理时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $productRequire)
        <tr>
            <td>{{ $productRequire->id }}</td>     
            <td>{{ $productRequire->name }}</td>
            <td>{{ $productRequire->province }}</td>
            <td>{{ $productRequire->city }}</td>
            <td>{{ $productRequire->similar_sku }}</td>
            <td>{{ $productRequire->competition_url }}</td>
            <td>{{ $productRequire->remark }}</td>
            <td>{{ $productRequire->expected_date }}</td>
            <td>{{ $productRequire->neederName ? $productRequire->neederName->name : '' }}</td>
            <td>{{ $productRequire->needer_shop_id }}</td>
            <td>{{ $productRequire->createdByName ? $productRequire->createdByName->name : '' }}</td>
            <td>{{ $productRequire->created_at }}</td>
            <td>{{ $productRequire->status }}</td>
            <td>{{ $productRequire->userName ? $productRequire->userName->name : '' }}</td>
            <td>{{ $productRequire->handle_time }}</td>
            <td>
                <a href="{{ route('productRequire.show', ['id'=>$productRequire->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('productRequire.edit', ['id'=>$productRequire->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href='#' class='btn btn-primary btn-xs'>
                    <span class='glyphicon glyphicon-sunglasses'></span>处理
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $productRequire->id }}"
                   data-url="{{ route('productRequire.destroy', ['id' => $productRequire->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
