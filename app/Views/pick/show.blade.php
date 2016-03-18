@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>拣货单id</strong>: {{ $model->picklist_id }}
            </div>
            <div class="col-lg-2">
                <strong>类型</strong>: {{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多')}}
            </div>
            <div class="col-lg-2">
                <strong>拣货单状态</strong>: {{ $model->status_name }}
            </div>
        </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-heading">package信息</div>
        <div class="panel-body">
        @foreach($packages as $package)
            <div class='row'>
                <div class="col-lg-2">
                    <strong>包裹ID</strong>: {{ $package->id }}
                </div>
                <div class="col-lg-2">
                    <strong>状态</strong>: {{ $package->status }}
                </div>
            </div>
            @foreach($package->items as $item)
            <div class='row col-lg-offset-1'>
                <div class='col-lg-2'>
                    <strong>包裹条目id</strong>: {{ $item->id }}
                </div>
                <div class='col-lg-2'>
                    <strong>sku</strong>: {{ $item->items->sku }}
                </div>
                <div class='col-lg-2'>
                    <strong>数量</strong>: {{ $item->quantity }}
                </div>
                <div class='col-lg-2'>
                    <strong>备注</strong>: {{ $item->quantity }}
                </div>
            </div>
            @endforeach
        @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
            <div class="col-lg-4">
                <strong>更新时间</strong>: {{ $model->updated_at }}
            </div>
        </div>
    </div>
@stop