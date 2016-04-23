@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $model->id }}
            </div>
            <div class="col-lg-3">
                <strong>sku</strong>: {{ $model->sku }}
            </div>
            <div class="col-lg-3">
                <strong>产品中文名</strong>: {{ $model->name }}
            </div>
            <div class="col-lg-3">
                <strong>产品英文名</strong>: {{ $model->c_name }}
            </div>
        </div>

        <div class="panel-body">
            <div class="col-lg-3">
                <strong>产品中文别名</strong>: {{ $model->alias_name }}
            </div>
            <div class="col-lg-3">
                <strong>产品英文别名</strong>: {{ $model->alias_cname }}
            </div>
            <div class="col-lg-3">
                <strong>创建时间</strong>: {{ $model->created_at }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Feature属性:</div>
        <div class="panel-body">
            @foreach($model->product->featureTextValues as $featureModel)
            <div class="col-lg-3">
                <strong>{{$featureModel->featureName->name}}</strong>: {{$featureModel->feature_value}}
            </div>
            @endforeach
        </div> 
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">供应商信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>供应商ID</strong>: {{ $model->supplier_id }}
            </div>
            <div class="col-lg-3">
                <strong>供应商名</strong>: {{ $model->supplier->name }}
            </div>
            <div class="col-lg-3">
                <strong>供应商信息</strong>: {{ $model->supplier_info }}
            </div>
            <div class="col-lg-3">
                <strong>辅助供应商</strong>: <?php if($model->second_supplier_id==0){echo "无辅供应商";}else{echo $model->product->supplier->where('id',$model->second_supplier_id)->get()->first()->name;} ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">采购信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>销售链接</strong>: {{ $model->product->product_sale_url }}
            </div>
            <div class="col-lg-3">
                <strong>采购链接</strong>: {{ $model->purchase_url }}
            </div>
            <div class="col-lg-3">
                <strong>供应商sku</strong>: {{ $model->supplier_sku }}
            </div>
            <div class="col-lg-3">
                <strong>采购价</strong>: {{ $model->purchase_price }}
            </div>
            <div class="col-lg-3">
                <strong>采购物流费</strong>: {{ $model->purchase_carriage }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"> :</div>
        <div class="panel-body">
            <?php 
                $inventory = 0;
                $amount = 0;
                foreach($model->stocks as $stock){
                    $inventory += $stock->all_quantity;
                    $amount += $stock->amount;
                }
                $baoque="false";
                $baodeng = "false";
                $tuhuobuyi = "false";
                $canci = "false";
                $purchaseArray = $model->purchase->toArray();
                foreach ($purchaseArray as $arr) {
                    if($arr['active']==1&&$arr['active_status']>0)$baoque="true";
                    if($arr['active']==2&&$arr['active_status']>0)$baodeng="true";
                    if($arr['active']==3&&$arr['active_status']>0)$canci="true";
                    if($arr['active']==4&&$arr['active_status']>0)$tuhuobuyi="true";
                }
            ?>
            <div class="col-lg-2">
                <strong>库存</strong>: {{ $inventory }}
            </div>
            <div class="col-lg-2">
                <strong>库存金额</strong>: {{ $amount }}
            </div>

            <div class="col-lg-2">
                <strong>报缺状态</strong>: {{$baoque}}
            </div>
            <div class="col-lg-2">
                <strong>报等状态</strong>: {{$baodeng}}
            </div>
            <div class="col-lg-2">
                <strong>残次品</strong>: {{$canci}}
            </div>
            <div class="col-lg-2">
                <strong>图货不一</strong>: {{$tuhuobuyi}}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">物流信息 :</div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>item尺寸</strong>: {{ $model->product_size }}
            </div>
            <div class="col-lg-3">
                <strong>item包装尺寸</strong>: {{ $model->package_size }}
            </div>
            <div class="col-lg-3">
                <strong>item重量</strong>: {{ $model->weight }}
            </div>
            <div class="col-lg-3">
                <strong>物流限制</strong>: 
                <?php 
                    $carriage_key_arr = explode(',',$model->carriage_limit);
                    foreach(config('product.carriage_limit') as $carriage_key=>$carriage_value){
                        if(in_array($carriage_key, $carriage_key_arr)){
                            echo $carriage_value.",";
                        }
                    }

                ?>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>包装限制</strong>: 
                <?php 
                    $package_key_arr = explode(',',$model->package_limit);
                    foreach(config('product.package_limit') as $package_key=>$package_value){
                        if(in_array($package_key, $package_key_arr)){
                            echo $package_value.",";
                        }
                    }

                ?>
            </div>
            <div class="col-lg-3">
                <strong>状态</strong>: <?php if($model->is_sale==1){echo "可售";}else{echo "不可售";} ?>
            </div>
            <div class="col-lg-3">
                <strong>投诉比例</strong>: 
            </div>
            <div class="col-lg-3">
                <strong>退款率</strong>: 
            </div>
            <div class="col-lg-3">
                <strong>备注</strong>: {{ $model->remark }}
            </div>
            
        </div>
    </div>
@stop
