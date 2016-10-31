@extends('common.table')
@section('tableHeader')
    <th>待入库sku</th>
    <th>采购单ID</th>
    <th>采购条目ID</th>
    <th>仓库</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $_data)
        <tr>
            <td>{{ $_data->sku }}</td>
            <td>{{ $_data->purchase_order_id }}</td>
            <td>{{ $_data->id }}</td>
            <td>{{ $_data->warehouse?$_data->warehouse->name:'' }}</td>
            <td>
                <a href="" data-toggle="modal" data-target="#wpedit_{{$_data->id}}">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑库位
                </a>
            </td>
        </tr>

    @endforeach
@stop
