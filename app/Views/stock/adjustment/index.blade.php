@extends('common.table')
@section('tableHeader')
    <th class='sort' data-field='id'>ID</th>
    <th class='sort' data-field='adjust_form_id'>调整单号</th>
    <th>仓库</th>
    <th>备注</th>
    <th>调整人</th>
    <th>状态</th>
    <th>审核人</th>
    <th class='sort' data-field='check_time'>审核时间</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $adjust)
        <tr>
            <td>{{ $adjust->id }}</td>
            <td>{{ $adjust->adjust_form_id }}</td>
            <td>{{ $adjust->warehouse ? $adjust->warehouse->name : '' }}</td>
            <td>{{ $adjust->remark }}</td>
            <td>{{ $adjust->adjustByName ? $adjust->adjustByName->name : '' }} </td>
            <td>{{ $adjust->status ? ($adjust->status == '1' ? '未通过' : '已通过') : '未审核' }}</td>
            <td>{{ $adjust->checkByName ? $adjust->checkByName->name : '' }}</td>
            <td>{{ $adjust->check_time }}</td>
            <td>{{ $adjust->created_at }}</td>
            <td>
                <a href="{{ route('stockAdjustment.show', ['id'=>$adjust->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                @if(!$adjust->status)
                <a href="{{ route('stockAdjustment.edit', ['id'=>$adjust->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="{{ route('stockAdjustment.check', ['id'=>$adjust->id]) }}"  class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span>
                    审核
                </a>
                @endif
                @if($adjust->status == '0')
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $adjust->id }}"
                   data-url="{{ route('stockAdjustment.destroy', ['id' => $adjust->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
                @endif
            </td>
        </tr>
    @endforeach
@stop