@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-1">
                <strong>拣货单id</strong>: {{ $model->pick_id }}
            </div>
            <div class="col-lg-1">
                <strong>类型</strong>: {{ $model->type == '0' ? '单单' ($model->type == '1' ? '单多' : '多多')}}
            </div>
            <div class="col-lg-2">
                <strong>拣货单状态</strong>: {{ $model->status }}
            </div>
        </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-heading">package信息</div>
        <div class="panel-body">
        @foreach($packages as $package)
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $package->id }}
            </div>
            <div class="col-lg-1">
                <strong>状态</strong>: {{ $package->status }}
            </div>
            @foreach($package->orderitems as $orderitem)
            <div class='row'>
                <div class='col-lg-2'>
                    <strong>ID</strong>: {{ $orderitem->id }}
                </div>
                <div class='col-lg-2'>
                    <strong>ID</strong>: {{ $orderitem->items->sku }}
                </div>
                <div class='col-lg-2'>
                    <strong>ID</strong>: {{ $orderitem->quantity }}
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