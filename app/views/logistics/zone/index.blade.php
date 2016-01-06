@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('name') }}">物流分区{!! Sort::label('name') !!}</th>
    <th class="sort" data-url="{{ Sort::url('logistics_id') }}">物流方式{!! Sort::label('logistics_id') !!}</th>
    <th>国家</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th class="sort" data-url="{{ Sort::url('updated_at') }}">更新时间{!! Sort::label('updated_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $zone)
        <tr>
            <td>{{ $zone->id }}</td>
            <td>{{ $zone->name }}</td>
            <td>{{ $zone->logistics->logistics_type}}</td>
            <td>{{ $zone->countries }}</td>
            <td>{{ $zone->updated_at }}</td>
            <td>{{ $zone->created_at }}</td>
            <td>
                <a href="{{ route('logisticsZone.show', ['id'=>$zone->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsZone.edit', ['id'=>$zone->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $zone->id }}"
                   data-url="{{ route('logisticsZone.destroy', ['id' => $zone->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
