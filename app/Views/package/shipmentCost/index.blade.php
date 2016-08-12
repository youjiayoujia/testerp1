@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>批次号</th>
    <th>计费重量(kg)</th>
    <th>理论重量(kg)</th>
    <th>计费总运费(元)</th>
    <th>理论总运费(元)</th>
    <th>总条数</th>
    <th>各渠道均价(元)</th>
    <th>导入人</th>
    <th class="sort" data-field="created_at">导入时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $shipmentCost)
        <tr>
            <td>{{ $shipmentCost->id }}</td>
            <td>{{ $shipmentCost->shipmentCostNum }}</td>
            <td>{{ $shipmentCost->all_weight }}</td>
            <td>{{ $shipmentCost->theory_weight }}</td>
            <td>{{ $shipmentCost->all_shipment_cost }}</td>
            <td>{{ $shipmentCost->theory_shipment_cost }}</td>
            <td>{{ $shipmentCost->number }}</td>
            <td>{{ $shipmentCost->average_price }}</td>
            <td>{{ $shipmentCost->import_by }}</td>
            <td>{{ $shipmentCost->created_at }}</td>
            <td>
                <a href="{{ route('shipmentCost.show', ['id'=>$shipmentCost->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('shipmentCost.edit', ['id'=>$shipmentCost->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $shipmentCost->id }}"
                   data-url="{{ route('shipmentCost.destroy', ['id' => $shipmentCost->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
<div class="btn-group">
    <a class="btn btn-success import" href="javascript:">
        导入数据
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-success export" href="javascript:">
        导出模板
    </a>
</div>
<div class="btn-group">
    <a class="btn btn-primary implodePackage" href="javascript:">
        批量删除
    </a>
</div>
@stop
@section('childJs')
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.export', function(){
        location.href = "{{ route('shipmentCost.export') }}";
    });

    $(document).on('click', '.import', function(){
        location.href = "{{ route('shipmentCost.import') }}";
    });
})
</script>
@stop