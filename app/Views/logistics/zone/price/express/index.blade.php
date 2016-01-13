@extends('common.table')
@section('tableHeader')
    <th class="sort" data-url="{{ Sort::url('id') }}">ID{!! Sort::label('id') !!}</th>
    <th class="sort" data-url="{{ Sort::url('name') }}">物流分区报价{!! Sort::label('name') !!}</th>
    <th class="sort" data-url="{{ Sort::url('shipping') }}">种类{!! Sort::label('shipping') !!}</th>
    <th class="sort" data-url="{{ Sort::url('fixed_weight') }}">首重(kg){!! Sort::label('fixed_weight') !!}</th>
    <th class="sort" data-url="{{ Sort::url('fixed_price') }}">首重价格(/kg){!! Sort::label('fixed_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('continued_weight') }}">续重(kg){!! Sort::label('continued_weight') !!}</th>
    <th class="sort" data-url="{{ Sort::url('continued_price') }}">续重价格(/kg){!! Sort::label('continued_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('other_fixed_price') }}">其他固定费用{!! Sort::label('other_fixed_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('other_scale_price') }}">其他比例费用(%){!! Sort::label('other_scale_price') !!}</th>
    <th class="sort" data-url="{{ Sort::url('discount') }}">最后折扣{!! Sort::label('discount') !!}</th>
    <th class="sort" data-url="{{ Sort::url('created_at') }}">创建时间{!! Sort::label('created_at') !!}</th>
    <th class="sort" data-url="{{ Sort::url('updated_at') }}">更新时间{!! Sort::label('updated_at') !!}</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $zonePriceExpress)
        <tr>
            <td>{{ $zonePriceExpress->id }}</td>
            <td>{{ $zonePriceExpress->name }}</td>
            <td>{{ $zonePriceExpress->shipping == 'express' ? '快递' : ''}}</td>
            <td>{{ $zonePriceExpress->fixed_weight }}</td>
            <td>{{ $zonePriceExpress->fixed_price }}</td>
            <td>{{ $zonePriceExpress->continued_weight }}</td>
            <td>{{ $zonePriceExpress->continued_price }}</td>
            <td>{{ $zonePriceExpress->other_fixed_price }}</td>
            <td>{{ $zonePriceExpress->other_scale_price }}</td>
            <td>{{ $zonePriceExpress->discount }}</td>
            <td>{{ $zonePriceExpress->updated_at }}</td>
            <td>{{ $zonePriceExpress->created_at }}</td>
            <td>
                <a href="{{ route('logisticsZonePriceExpress.show', ['id'=>$zonePriceExpress->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsZonePriceExpress.edit', ['id'=>$zonePriceExpress->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $zonePriceExpress->id }}"
                   data-url="{{ route('logisticsZonePriceExpress.destroy', ['id' => $zonePriceExpress->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
