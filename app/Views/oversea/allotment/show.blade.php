@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">调拨单基础信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $model->id }}
        </div>
        <div class="col-lg-2">
            <strong>调拨单号</strong>: {{ $model->allotment_num }}
        </div>
        <div class="col-lg-2">
            <strong>调出仓库</strong>: {{ $model->outWarehouse ? $model->outWarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调入仓库</strong>: {{ $model->inWarehouse ? $model->inWarehouse->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>调拨人</strong>: {{ $model->allotmentBy ? $model->allotmentBy->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>状态</strong>: {{ config('oversea.allotmentStatus')[$model->status] }}
        </div>
        <div class="col-lg-2">
            <strong>审核人</strong>: {{ $model->checkBy ? $model->checkBy->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>审核状态</strong>: {{ $model->check_status == 'new' ? '未审核' : ($model->check_status == 'fail' ? '未审核' : '已审核') }}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">调拨单sku列表</div>
    <div class="panel-body">
    @foreach($allotments as $allotment)
    <div class='row'>
        <div class="col-lg-2">
            <strong>sku</strong>: {{ $allotment->item ? $allotment->item->sku : '' }}
        </div>
        <div class="col-lg-2">
            <strong>库位</strong>: {{ $allotment->position ? $allotment->position->name : '' }}
        </div>
        <div class="col-lg-2">
            <strong>数量</strong>: {{ $allotment->quantity }}
        </div>
    </div>
    @endforeach
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr class='danger'>
        <th>名称</th>
        <th>值</th>
        <th>公式</th>
        </tr>
    </thead>
    <tbody>
    <tr class='success'><td>ERP总重量</td><td>{{$all_weight}}kg</td><td>ERP总重量=erp重量x商品实发数 ，累加</td></tr>
    <tr class='success'><td>实测总重量</td><td>{{$model->boxes ? $model->boxes->sum('weight') : ''}}kg</td><td>实测总重量=各箱重量之和</td></tr>
    <tr class='success'><td>实测体积</td><td>{{$volumn}}</td><td>实测体积=各箱体积重之和体积重=（长*宽*高）÷5000</td></tr>
    <tr class='success'><td>实际总运费</td><td>{{$model->boxes ? $model->boxes->sum('fee') : ''}}kg</td><td>人工手动输入</td></tr>
    </tbody>
</table>

<div class="panel panel-info">
    <div class="panel-heading">装箱信息</div>
    <div class="panel-body">
    @foreach($boxes as $key => $box)
    <div class='row'>
        <div class="form-group col-lg-3">
            <label>箱号</label>
            <input type='text' class="form-control" value="{{ $box->boxnum }}">
        </div>
        <div class="form-group col-lg-3">
            <label>物流方式</label>
            <input type='text' class="form-control" value="{{ $box->logistics ? $box->logistics->code : '' }}">
        </div>
        <div class="form-group col-lg-3">
            <label>体积(m3)</label>
            <input type='text' class="form-control" value="{{ $box->length . '*' . $box->width . '*' . $box->height }}">
        </div>
        <div class="form-group col-lg-3">
            <label>预估重量(kg)</label>
            <input type='text' class="form-control" value="{{$arr[$key]}}">
        </div>
        <div class="form-group col-lg-3">
            <label>实际重量(kg)</label>
            <input type='text' class="form-control" value="{{ $box->weight }}">
        </div>
        <div class="form-group col-lg-3">
            <label>体积重</label>
            <input type='text' class="form-control" value="{{ round($box->length * $box->height * $box->width / 5000, 3) }}">
        </div>
        <div class="form-group col-lg-3">
            <label>体积系数</label>
            <input type='text' class="form-control" value="{{ $box->weight != 0 ? round($box->length * $box->height * $box->width / 5000 / $box->weight, 4) : '重量为0' }}">
        </div>
    </div>


    <table class="table table-bordered">
        <thead>
            <tr>
            <th>sku</th>
            <th>数量</th>
            </tr>
        </thead>
        <tbody>
        @foreach($box->forms as $form)
        <tr class='success'>
            <td>{{ $form->sku }}</td>
            <td>{{ $form->quantity }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
    @endforeach
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading">日志信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>创建时间</strong>: {{ $model->created_at }}
        </div>
        <div class="col-lg-2">
            <strong>更新时间</strong>: {{ $model->updated_at }}
        </div>
    </div>
</div>
@stop