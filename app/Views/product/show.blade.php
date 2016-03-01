@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>spu</strong>: {{ $model->spu->spu }}
            </div>
            <div class="col-lg-3">
                <strong>model</strong>: {{ $model->model }}
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>分类</strong>: {{ $model->catalog->name }}
            </div>
            <div class="col-lg-3">
                <strong>产品name</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>产品中文名</strong>: {{ $model->c_name }}
            </div>
        </div>

        <div class="panel-body">
            <div class="col-lg-3">
                <strong>产品尺寸</strong>: {{ $model->product_size }}
            </div>
            <div class="col-lg-3">
                <strong>产品包装尺寸</strong>: {{ $model->package_size }}
            </div>
            <div class="col-lg-3">
                <strong>产品重量</strong>: {{ $model->weight }}
            </div>
        </div>       
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Feature属性:</div>
        <div class="panel-body">
            @foreach($model->productFeatureValue as $featureModel)
            <div class="col-lg-3">
                <strong>{{$featureModel->featureName->name}}</strong>: {{$featureModel->feature_value}}
            </div>
            @endforeach
        </div> 
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">供应商信息:</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>主供应商</strong>: {{ $model->supplier->name }}
            </div>
            <div class="col-lg-3">
                <strong>采购链接</strong>: <a href="http://{{ $model->purchase_url }}" target="_blank">{{ $model->purchase_url }}</a>
            </div>
            <div class="col-lg-3">
                <strong>采购价</strong>: {{ $model->purchase_price }}
            </div>
            <div class="col-lg-3">
                <strong>采购物流费</strong>: {{ $model->purchase_carriage }}
            </div>
        </div>
        <div class="panel-body">
        <div class="col-lg-3">
                <strong>主供应商sku</strong>: {{ $model->supplier_sku }}
            </div>
            <div class="col-lg-3">
                <strong>辅供应商</strong>: <?php if($model->second_supplier_id==0){echo "无辅供应商";}else{echo $model->supplier->where('id',$model->second_supplier_id)->get()->first()->name;} ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">其他信息:</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>物流限制</strong>: {{ $model->carriage_limit }}
            </div>
            <div class="col-lg-3">
                <strong>物流限制1</strong>: {{ $model->carriage_limit_1 }}
            </div>
            <div class="col-lg-3">
                <strong>包装限制</strong>: {{ $model->package_limit }}
            </div>
            <div class="col-lg-3">
                <strong>包装限制1</strong>: {{ $model->package_limit_1 }}
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>上传人</strong>: {{ $model->upload_user }}
            </div>
            <div class="col-lg-3">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">产品图片 :</div>
        <div class="panel-body">
            <?php if(isset($model->image->name)){ ?>
            <img src="{{ asset($model->image->path) }}/{{$model->image->name}}" width="600px" >
            <?php }else{ ?>
                无图片
            <?php } ?>
        </div>
    </div>
@stop