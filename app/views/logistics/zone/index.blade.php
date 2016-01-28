@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('zone') }}">物流分区{!! Sort::label('zone') !!}</th>
    <th class="sort" data-url="{{ Sort::url('logistics_id') }}">物流方式{!! Sort::label('logistics_id') !!}</th>
    <th>国家</th>
    <th class="sort" data-url="{{ Sort::url('shipping') }}">种类{!! Sort::label('shipping') !!}</th>
    <th class="sort" data-url="{{ Sort::url('fixed_weight') }}">首重(kg){!! Sort::label('fixed_weight') !!}</th>
    <th class="sort" data-url="{{ Sort::url('fixed_price') }}">首重价格(/kg){!! Sort::label('fixed_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('continued_weight') }}">续重(kg){!! Sort::label('continued_weight') !!}</th>
    <th class="sort" data-url="{{ Sort::url('continued_price') }}">续重价格(/kg){!! Sort::label('continued_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('other_fixed_price') }}">其他固定费用{!! Sort::label('other_fixed_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('other_scale_price') }}">其他比例费用(%){!! Sort::label('other_scale_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('price') }}">价格(/kg){!! Sort::label('price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('other_price') }}">其他费用{!! Sort::label('other_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('discount') }}">最后折扣{!! Sort::label('discount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th class="sort" data-url="{{ Sort::url('updated_at') }}">更新时间{!! Sort::label('updated_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $zone)
        <tr>
            <td>{{ $zone->id }}</td>
            <td>{{ $zone->zone }}</td>
            <td>{{ $zone->logistics->logistics_type}}</td>
            <td>{{ $zone->country_id }}</td>
            <td>{{ $zone->shipping_id == 'express' ? '快递' : '小包'}}</td>
            <td>{{ $zone->fixed_weight }}</td>
            <td>{{ $zone->fixed_price }}</td>
            <td>{{ $zone->continued_weight }}</td>
            <td>{{ $zone->continued_price }}</td>
            <td>{{ $zone->other_fixed_price }}</td>
            <td>{{ $zone->other_scale_price }}</td>
            <td>{{ $zone->price }}</td>
            <td>{{ $zone->other_price }}</td>
            <td>{{ $zone->discount }}</td>
            <td>{{ $zone->updated_at }}</td>
            <td>{{ $zone->created_at }}</td>
            <td>
                <a href="{{ route('logisticsZone.show', ['id'=>$zone->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsZone.edit', ['id'=>$zone->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                @if($zone->shipping_id == 'packet')
                    <a href="{{ route('countPacket', ['id'=>$zone->id]) }}" class="btn btn-success btn-xs">
                        <span class="glyphicon glyphicon-plus"></span> 运费
                    </a>
                @endif
                @if($zone->shipping_id == 'express')
                    <a href="{{ route('countExpress', ['id'=>$zone->id]) }}" class="btn btn-success btn-xs">
                        <span class="glyphicon glyphicon-plus"></span> 运费
                    </a>
                @endif
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $zone->id }}"
                   data-url="{{ route('logisticsZone.destroy', ['id' => $zone->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
