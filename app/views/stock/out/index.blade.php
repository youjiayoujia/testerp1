@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th class='sort' data-field='item_id'>item号</th>
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
