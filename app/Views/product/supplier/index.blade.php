@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th>名称</th>
    <th>公司</th>
    <th>详细地址</th>
    <th>供货商类型</th>
    <th>销售网址</th>
    <th>供货商官网</th>
    <th>联系人</th>
    <th class='sort' data-field='telephone'>电话</th>
    <th>电子邮件</th>
    <th>采购员</th>
    <th class='sort' data-field='level'>供货商等级</th>
    <th>创建人</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $supplier)
        <tr>
            <td>{{ $supplier->id }}</td>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->company }}</td>
            <td>{{ $supplier->address }}</td>
            <td>{{ $supplier->type ? ($supplier->type == '1' ? '线上' : '做货') : '线下' }} </td>
            <td>{{ $supplier->url }}</td>
            <td>{{ $supplier->official_url }}</td>
            <<td>{{ $supplier->contact_name }}</td>
            <td>{{ $supplier->telephone }}</td>
            <td>{{ $supplier->email }}</td>
            <td>{{ $supplier->purchaseName ? $supplier->purchaseName->name : '' }}</td>
            <td>{{ $supplier->levelByName ? $supplier->levelByName->name : '' }}</td>
            <td>{{ $supplier->createdByName ? $supplier->createdByName->name : '' }}</td>
            <td>{{ $supplier->created_at }}</td>
            <td>
                <a href="{{ route('productSupplier.show', ['id'=>$supplier->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('productSupplier.edit', ['id'=>$supplier->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $supplier->id }}"
                   data-url="{{ route('productSupplier.destroy', ['id' => $supplier->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success" href="{{ route('supplierLevel.index') }}">
        <i class="glyphicon glyphicon-plus"></i> 供货商评级
    </a>
</div>
@parent
@stop
