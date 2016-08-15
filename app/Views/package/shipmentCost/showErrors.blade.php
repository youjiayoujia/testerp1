@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-4">
                <strong>批次号</strong>: {{ $model->shipmentCostNum }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">导入详情</div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover sortable">
                <thead>
                <tr>
                <th>批次号</th>
                <th>挂号码</th>
                <th>渠道名称</th>
                <th>异常描述</th>
                <th>导入时间</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->parent ? $item->parent->shipmentCostNum : '' }}</td>
                    <td>{{ $item->hang_num }}</td>
                    <td>{{ $item->channel_name }}</td>
                    <td>{{ $item->remark }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>   
                @endforeach
                </tbody>
            </table>
            <?php echo $items->render(); ?>
        </div>
    </div>
@stop