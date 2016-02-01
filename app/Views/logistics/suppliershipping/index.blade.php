@extends('common.table')
@section('tableHeader')
    <th class="sort">ID</th>
    <th>物流商物流方式</th>
    <th>物流商</th>
    <th>备注</th>
    <th class="sort">创建时间</th>
    <th class="sort">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $supplierShipping)
        <tr>
            <td>{{ $supplierShipping->id }}</td>
            <td>{{ $supplierShipping->logistics_type }}</td>
            <td>{{ $supplierShipping->supplier->name }}</td>
            <td>{{ $supplierShipping->remark }}</td>
            <td>{{ $supplierShipping->updated_at }}</td>
            <td>{{ $supplierShipping->created_at }}</td>
            <td>
                <a href="{{ route('supplierShipping.show', ['id'=>$supplierShipping->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('supplierShipping.edit', ['id'=>$supplierShipping->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $supplierShipping->id }}"
                   data-url="{{ route('supplierShipping.destroy', ['id' => $supplierShipping->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
