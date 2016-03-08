@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-2">
                <strong>盘点表ID</strong>: {{ $model->taking_id }}
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
                    <div class="col-lg-2">
                        <strong>sku</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->items ? $stockTakingForm->stock->items->sku : '' : ''}}
                    </div>
                    <div class="col-lg-2">
                        <strong>仓库</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->warehouse ? $stockTakingForm->stock->warehouse->name : '' : ''}}
                    </div>
                    <div class="col-lg-2">
                        <strong>库位</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->position ? $stockTakingForm->stock->position->name : '' : ''}}
                    </div>
                    <div class="col-lg-2">
                        <strong>总数量</strong>: {{ $stockTakingForm->stock ? $stockTakingForm->stock->all_quantity : '' }}
                    </div>
                    <div class="col-lg-2">
                        <strong>实盘数量</strong>: {{ $stockTakingForm->quantity }}
                    </div> 
                    <div class="col-lg-2">
                        <strong>盘点状态</strong>: {{ $stockTakingForm->stock_taking_status == 'more' ? '盘盈' : ($stockTakingForm->stock_taking_status == 'equal' ? '不处理' : '盘亏') }}
                    </div> 
                </div> 
                @endif
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
    <div class="panel-heading">出库信息</div>
    <div class="panel-body">
        @foreach($stockouts as $stockout)
            <div class='row'>
                <div class="col-lg-2">
                    <strong>sku</strong>: {{ $stockout->stock ? $stockout->stock->items ? $stockout->stock->items->sku : '' : '' }}
                </div>
                <div class="col-lg-2">
                    <strong>出库数量</strong>: {{ $stockout->quantity }}
                </div>
                <div class="col-lg-2">
                    <strong>出库金额(￥)</strong>: {{ $stockout->amount }}
                </div>
                <div class="col-lg-2">
                    <strong>出库库位</strong>: {{ $stockout->stock ? $stockout->stock->position ? $stockout->stock->position->name : '' : '' }}
                </div>
                <div class="col-lg-2">
                    <strong>出库时间</strong>: {{ $stockout->created_at }}
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">入库信息</div>
        <div class="panel-body">
        @foreach($stockins as $stockin)
        <div class='row'>
            <div class="col-lg-2">
                <strong>sku</strong>: {{ $stockin->stock ? $stockin->stock->items ? $stockin->stock->items->sku : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>入库数量</strong>: {{ $stockin->quantity }}
            </div>
            <div class="col-lg-2">
                <strong>入库金额(￥)</strong>: {{ $stockin->amount }}
            </div>
            <div class="col-lg-2">
                <strong>入库库位</strong>: {{ $stockin->stock ? $stockin->stock->position ? $stockin->stock->position->name : '' : '' }}
            </div>
            <div class="col-lg-2">
                <strong>入库时间</strong>: {{ $stockin->created_at }}
            </div>
        </div>
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