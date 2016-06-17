@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">编号</th>
    <th>协议客户</th>
    <th>发件地址</th>
    <th>邮编</th>
    <th>电话</th>
    <th>退件单位</th>
    <th>寄件人</th>
    <th>备注</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $emailTemplate)
        <tr>
            <td>{{ $emailTemplate->id }}</td>
            <td>{{ $emailTemplate->customer }}</td>
            <td>{{ $emailTemplate->address }}</td>
            <td>{{ $emailTemplate->zipcode }}</td>
            <td>{{ $emailTemplate->phone }}</td>
            <td>{{ $emailTemplate->unit }}</td>
            <td>{{ $emailTemplate->sender }}</td>
            <td>{{ $emailTemplate->remark }}</td>
            <td>{{ $emailTemplate->updated_at }}</td>
            <td>{{ $emailTemplate->created_at }}</td>
            <td>
                <a href="{{ route('logisticsEmailTemplate.show', ['id'=>$emailTemplate->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsEmailTemplate.edit', ['id'=>$emailTemplate->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $emailTemplate->id }}"
                   data-url="{{ route('logisticsEmailTemplate.destroy', ['id' => $emailTemplate->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop