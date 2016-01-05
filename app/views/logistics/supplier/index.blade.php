@extends('common.table')
@section('tableHeader')
    <th>ID</th>
    <th>物流商名称</th>
    <th class="sort" data-url="{{ Sort::url('customer_id') }}">客户ID{!! Sort::label('customer_id') !!}</th>
    <th>密钥</th>
    <th>是否有API</th>
    <th>客户经理</th>
    <th>客户经理联系方式</th>
    <th>技术人员</th>
    <th>技术联系方式</th>
    <th>备注</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th class="sort" data-url="{{ Sort::url('updated_at') }}">更新时间{!! Sort::label('updated_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $supplier)
        <tr>
            <td>{{ $supplier->id }}</td>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->customer_id }}</td>
            <td>{{ $supplier->secret_key }}</td>
            <td>{{ $supplier->is_api == 'Y' ? '有' : '没有' }}</td>
            <td>{{ $supplier->client_manager }}</td>
            <td>{{ $supplier->manager_tel }}</td>
            <td>{{ $supplier->technician }}</td>
            <td>{{ $supplier->technician_tel }}</td>
            <td>{{ $supplier->remark }}</td>
            <td>{{ $supplier->updated_at }}</td>
            <td>{{ $supplier->created_at }}</td>
            <td>
                <a href="{{ route('logisticsSupplier.show', ['id'=>$supplier->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsSupplier.edit', ['id'=>$supplier->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $supplier->id }}"
                   data-url="{{ route('logisticsSupplier.destroy', ['id' => $supplier->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
