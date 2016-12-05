@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>调拨单号</th>
    <th>调出仓库</th>
    <th>调入仓库</th>
    <th>调拨人</th>
    <th>状态</th>
    <th>审核人</th>
    <th>审核状态</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $overseaAllotment)
        <tr>
            <td>{{ $overseaAllotment->id }}</td>
            <td>{{ $overseaAllotment->allotment_num }}</td>
            <td>{{ $overseaAllotment->outWarehouse ? $overseaAllotment->outWarehouse->name : ''}}</td>
            <td>{{ $overseaAllotment->inWarehouse ? $overseaAllotment->inWarehouse->name : ''}}</td>
            <td>{{ $overseaAllotment->allotmentBy ? $overseaAllotment->allotmentBy->name : ''}}</td>
            <td>{{ config('oversea.allotmentStatus')[$overseaAllotment->status] }}</td>
            <td>{{ $overseaAllotment->checkBy ? $overseaAllotment->checkBy->name : ''}}</td>
            <td>{{ $overseaAllotment->check_status == 'new' ? '未审核' : ($overseaAllotment->check_status == 'fail' ? '未审核' : '已审核')}}</td>
            <td>{{ $overseaAllotment->created_at }}</td>
            <td>
                <a href="{{ route('overseaAllotment.show', ['id'=>$overseaAllotment->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('overseaAllotment.edit', ['id'=>$overseaAllotment->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $overseaAllotment->id }}"
                   data-url="{{ route('overseaAllotment.destroy', ['id' => $overseaAllotment->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
