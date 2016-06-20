@extends('common.table')
@section('tableHeader')
    <th>ID</th>
    <th>物流分区</th>
    <th>物流方式</th>
    <th>物流方式简码</th>
    <th>计算方式</th>
    <th>首重(kg)</th>
    <th>首重价格(/kg)</th>
    <th>续重(kg)</th>
    <th>续重价格(/kg)</th>
    <th>其他固定费用</th>
    <th>最后折扣</th>
    <th>是否通折</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $zone)
        <tr>
            <td>{{ $zone->id }}</td>
            <td>{{ $zone->zone }}</td>
            <td>{{ $zone->logistics ? $zone->logistics->logistics_type : '' }}</td>
            <td>{{ $zone->logistics ? $zone->logistics->short_code : '' }}</td>
            <td>{{ $zone->type == 'first' ? '方式一' : '方式二' }}</td>
            <td>{{ $zone->fixed_weight }}</td>
            <td>{{ $zone->fixed_price }}</td>
            <td>{{ $zone->continued_weight }}</td>
            <td>{{ $zone->continued_price }}</td>
            <td>{{ $zone->other_fixed_price }}</td>
            <td>{{ $zone->discount }}</td>
            <td>{{ $zone->discount_weather_all ? '是' : '否'}}</td>
            <td>{{ $zone->created_at }}</td>
            <td>{{ $zone->updated_at }}</td>
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
