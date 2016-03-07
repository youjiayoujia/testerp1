@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>盘点表ID</strong>: {{ $model->taking ? $model->taking->taking_id : '' }}
            </div>
            <div class="col-lg-2">
                <strong>调整人</strong>: {{ $model->adjustmentByName ? $model->adjustmentByName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>调整时间</strong>: {{ $model->adjustment_time }}
            </div>
            <div class="col-lg-2">
                <strong>审核人</strong>: {{ $model->checkByName ? $model->checkByName->name : '' }}
            </div>
            <div class="col-lg-2">
                <strong>审核时间</strong>: {{ $model->check_time }}
            </div>
            <div class="col-lg-2">
                <strong>审核状态</strong>: {{ $model->check_status == '1' ? '已审核' : '未审核' }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">盘点表信息</div>
        <div class="panel-body">
            @foreach($stockTakingForms as $stockTakingForm)
                @if($stockTakingForm->stock_taking_status != 'equal')
                <div class='row'>
                    <div class="col-lg-1">
                        <strong>sku</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->items ? $stockTakingForm->stock->items->sku : '' : ''}}
                    </div>
                    <div class="col-lg-1">
                        <strong>仓库</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->warehouse ? $stockTakingForm->stock->warehouse->name : '' : ''}}
                    </div>
                    <div class="col-lg-1">
                        <strong>库位</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->position ? $stockTakingForm->stock->position->name : '' : ''}}
                    </div>
                    <div class="col-lg-1">
                        <strong>可用数量</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->available_quantity : '' }}
                    </div>
                    <div class="col-lg-1">
                        <strong>hold数量</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->hold_quantity : '' }}
                    </div>
                    <div class="col-lg-2">
                        <strong>实盘数量</strong>: {{ $stockTakingForm->quantity }}
                    </div> 
                    <div class="col-lg-2">
                        <strong>盘点状态</strong>: {{ $stockTakingForm->stock_taking_status == 'more' ? '盘盈' : ($stockTakingForm->stock_taking_status == 'equal' ? '不处理' : '盘亏') }}
                    </div> 
                    <div class="col-lg-1">
                        <strong>是否盘点更新</strong>: {{ $stockTakingForm->stock_taking_yn == '1' ? '是' : '否' }}
                    </div> 
                </div> 
                @endif
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