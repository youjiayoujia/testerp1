@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th>物流方式简码</th>
    <th>物流方式名称</th>
    <th>种类</th>
    <th>仓库</th>
    <th>物流商</th>
    <th>物流商物流方式</th>
    <th>物流追踪网址</th>
    <th>API对接方式</th>
    <th>是否启用</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th class="sort" data-url="{{ Sort::url('updated_at') }}">更新时间{!! Sort::label('updated_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $logistics)
        <tr>
            <td>{{ $logistics->id }}</td>
            <td>{{ $logistics->short_code }}</td>
            <td>{{ $logistics->logistics_type }}</td>
            <td>{{ $logistics->shipping == 'express' ? '快递' : '小包' }}</td>
            <td>{{ $logistics->warehouse->name }}</td>
            <td>{{ $logistics->supplier->name }}</td>
            <td>{{ $logistics->type }}</td>
            <td>{{ $logistics->url }}</td>
            <td>{{ $logistics->api_docking }}</td>
            <td>{{ $logistics->is_enable == 'Y' ? '是' : '否' }}</td>
            <td>{{ $logistics->created_at }}</td>
            <td>{{ $logistics->updated_at }}</td>
            <td>
                <a href="{{ route('logistics.show', ['id'=>$logistics->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logistics.edit', ['id'=>$logistics->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                @if($logistics->api_docking == '号码池')
                    <a href="{{ route('logisticsCode.create', ['id'=>$logistics->id]) }}" class="btn btn-success btn-xs">
                        <span class="glyphicon glyphicon-plus"></span> 号码池
                    </a>
                @endif
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $logistics->id }}"
                   data-url="{{ route('logistics.destroy', ['id' => $logistics->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
