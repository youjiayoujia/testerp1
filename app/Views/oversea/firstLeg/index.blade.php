@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>物流名</th>
    <th>仓库</th>
    <th>运输方式</th>
    <th>公式</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $firstLeg)
        <tr>
            <td>{{ $firstLeg->id }}</td>
            <td>{{ $firstLeg->name }}</td>
            <td>{{ $firstLeg->warehouse ? $firstLeg->warehouse->name : ''}}</td>
            <td>{{ $firstLeg->transport }}</td>
            <td>{{ $firstLeg->formula }}</td>
            <td>{{ $firstLeg->created_at }}</td>
            <td>
                <a href="{{ route('firstLeg.show', ['id'=>$firstLeg->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('firstLeg.edit', ['id'=>$firstLeg->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $firstLeg->id }}"
                   data-url="{{ route('firstLeg.destroy', ['id' => $firstLeg->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
