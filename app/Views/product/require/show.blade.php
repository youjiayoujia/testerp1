@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">基础信息</div>
    <div class="panel-body">
        @if($require->img1)
            <div class="col-lg-2">
                <strong>图片1</strong>: <img src="{{ $require->img1 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($require->img2)
            <div class="col-lg-2">
                <strong>图片2</strong>: <img src="{{ $require->img2 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($require->img3)
            <div class="col-lg-2">
                <strong>图片3</strong>: <img src="{{ $require->img3 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($require->img4)
            <div class="col-lg-2">
                <strong>图片4</strong>: <img src="{{ $require->img4 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($require->img5)
            <div class="col-lg-2">
                <strong>图片5</strong>: <img src="{{ $require->img5 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        @if($require->img6)
            <div class="col-lg-2">
                <strong>图片6</strong>: <img src="{{ $require->img6 }}" alt='' class='img-rounded' width='170px' height='100px'/>
            </div>
        @endif
        
        <div class="col-lg-2">
            <strong>ID</strong>: {{ $require->id }}
        </div>
        <div class="col-lg-2">
            <strong>名称</strong>: {{ $require->name }}
        </div>
        <div class="col-lg-1">
            <strong>省</strong>: {{ $require->province }}
        </div>
        <div class="col-lg-1">
            <strong>市</strong>: {{ $require->city }}
        </div>
        <div class="col-lg-2">
            <strong>类似款sku</strong>: {{ $require->similar_sku }}
        </div>
        <div class="col-lg-2">
            <strong>竞争产品url</strong>: {{ $require->competition_url }}
        </div>
        <div class="col-lg-2">
            <strong>选款备注</strong>: {{ $require->remark }}
        </div>
        <div class="col-lg-2">
            <strong>期待上传日期</strong>: {{ $require->expected_date }}
        </div>
        <div class="col-lg-2">
            <strong>需求人id</strong>: {{ $require->needer_id }}
        </div>
        <div class="col-lg-2">
            <strong>需求店铺id</strong>: {{ $require->needer_shop_id }}
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">日志信息</div>
    <div class="panel-body">
        <div class="col-lg-2">
            <strong>创建人</strong>: {{ $require->created_by }}
        </div>
        <div class="col-lg-2">
            <strong>创建时间</strong>: {{ $require->created_at }}
        </div>
        <div class="col-lg-2">
            <strong>状态</strong>: {{ $require->status }}
        </div>
        <div class="col-lg-2">
            <strong>处理者</strong>: {{ $require->user_id }}
        </div>
        <div class="col-lg-2">
            <strong>处理时间</strong>: {{ $require->handle_time }}
        </div>
    </div>
</div>
@stop