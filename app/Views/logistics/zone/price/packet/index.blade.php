@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('name') }}">物流分区报价{!! Sort::label('name') !!}</th>
    <th class="sort" data-url="{{ Sort::url('shipping') }}">种类{!! Sort::label('shipping') !!}</th>
    <th class="sort" data-url="{{ Sort::url('price') }}">价格{!! Sort::label('price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('other_price') }}">其他费用{!! Sort::label('other_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('discount') }}">最后折扣{!! Sort::label('discount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th class="sort" data-url="{{ Sort::url('updated_at') }}">更新时间{!! Sort::label('updated_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $zonePricePacket)
        <tr>
            <td>{{ $zonePricePacket->id }}</td>
            <td>{{ $zonePricePacket->name }}</td>
            <td>{{ $zonePricePacket->shipping }}</td>
            <td>{{ $zonePricePacket->price }}</td>
            <td>{{ $zonePricePacket->other_price }}</td>
            <td>{{ $zonePricePacket->discount }}</td>
            <td>{{ $zonePricePacket->updated_at }}</td>
            <td>{{ $zonePricePacket->created_at }}</td>
            <td>
                <a href="{{ route('logisticsZonePricePacket.show', ['id'=>$zonePricePacket->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsZonePricePacket.edit', ['id'=>$zonePricePacket->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $zonePricePacket->id }}"
                   data-url="{{ route('logisticsZonePricePacket.destroy', ['id' => $zonePricePacket->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
