@extends('common.form')
@section('formAction') {{ route('product.store') }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
<?php 
//echo '<pre>';
  //          print_r($logisticsLimit);exit;
?>

    <div class="form-group">
        <label for="catalog_id">分类</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select id="catalog_id" class="form-control" name="catalog_id">
            <option value="">选择分类</option>
            @foreach($catalogs as $_catalogs)
                <option value="{{ $_catalogs->id }}">{{ $_catalogs->all_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="ajaxinsert">

    </div>

    <div class='row'>
        <div class="form-group col-md-3">
            <label for="color">产品中文名</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">产品英文名</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="产品英文名" name='name' value="{{ old('name') }}">
        </div>
    </div>

    <div class='row'>
        <div class="form-group col-md-3">
            <label for="size">主供应商</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class='form-control supplier' name="supplier_id"></select>
        </div>
        <div class="form-group col-md-3">
            <label for="size">主供应商货号</label>
            <input class="form-control" id="supplier_sku" placeholder="供应商货号" name='supplier_sku' value="{{ old('supplier_sku') }}">
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select class='form-control supplier' name="second_supplier_id"></select>
        </div>
        <div class="form-group col-md-3">
            <label for="size">辅供应商货号</label>
            <input class="form-control" id="second_supplier_sku" placeholder="辅供应商货号" name='second_supplier_sku' value="{{ old('second_supplier_sku') }}">
        </div>
        
    </div>

    <div class='row'>
        <div class="form-group col-md-3">
            <label for="color">采购链接</label>
            <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') }}">
        </div>
            <div class="form-group col-md-3">
            <label for="size">采购价(RMB)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购物流费(RMB)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购天数</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_day" placeholder="采购天数" name='purchase_day' value="{{ old('purchase_day') }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3">
            <label for="size">仓库</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select  class="form-control" name="warehouse_id">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{$warehouse->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="size">尺寸类型</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select  class="form-control" name="product_size">
                <option value="大">大</option>
                <option value="中">中</option>
                <option value="小">小</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">产品包装尺寸（cm）(长*宽*高)</label></label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') }}">
        </div>
            <div class="form-group col-md-2">
            <label for="size">产品重量(kg)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight" placeholder="产品重量" name='weight' value="{{ old('weight') }}">
        </div>
        
    </div>

    <div class="row">
        
        <div class="form-group col-md-3"><label for="color">采购负责人</label>
            <select class='form-control purchase_adminer' name="purchase_adminer"></select>
        </div>
        
        <div class="form-group col-md-3">
            <label for="color">url1</label>
            <input class="form-control" id="url1" placeholder="url" name='url1' value="{{ old('url1') }}">
        </div>
            <div class="form-group col-md-3">
            <label for="size">url2</label>
            <input class="form-control" id="url2" placeholder="url" name='url2' value="{{ old('url2') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">url3</label>
            <input class="form-control" id="url3" placeholder="url" name='url3' value="{{ old('url3') }}">
        </div>
    </div>

    <div class='row'>
        <div class="form-group col-md-12" style="padding-top:26px">
            <label for="color">物流限制</label>  
                @foreach($logisticsLimit as $carriage_limit)
                    <label>
                        <input type='checkbox' name='carriage_limit_arr[]' value='{{$carriage_limit->id}}'>{{$carriage_limit->name}}
                    </label>
                @endforeach   
        </div>
        <div class="form-group col-md-12" style="padding-top:26px">
            <label for="color">包装限制</label>
            @foreach($wrapLimit as $wrap_limit)
                <label>
                    <input type='checkbox' name='package_limit_arr[]' value='{{$wrap_limit->id}}'>{{$wrap_limit->name}}
                </label>
            @endforeach
        </div>

        <div class="form-group col-md-3">
            <label for="color">质检标准</label>
            <input class="form-control" id="quality_standard" placeholder="质检标准" name='quality_standard' value="{{ old('quality_standard') }}">
        </div> 
        <div class="form-group col-md-3">
            <label for="color">尺寸描述</label>
            <input class="form-control" id="size_description" placeholder="尺寸描述" name='size_description' value="{{ old('size_description') }}">
        </div>        
        <div class="form-group col-md-3">
            <label for="color">描述</label>
            <input class="form-control" id="description" placeholder="描述" name='description' value="{{ old('description') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') }}">
        </div>
    </div>

    <div class="row">    
        <div class="form-group col-md-3">
            <label for="color">申报中文</label>
            <input class="form-control" id="declared_cn" placeholder="申报中文" name='declared_cn' value="{{ old('declared_cn') }}">
        </div>
        
        <div class="form-group col-md-3">
            <label for="color">申报英文</label>
            <input class="form-control" id="declared_en" placeholder="申报英文" name='declared_en' value="{{ old('declared_en') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">申报价格</label>
            <input class="form-control" id="declared_value" placeholder="申报价格" name='declared_value' value="{{ old('declared_value') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">配件</label>
            <input class="form-control" id="parts" placeholder="配件" name='parts' value="{{ old('parts') }}">
        </div>
    </div>

@stop

@section('pageJs')
<script type="text/javascript">
    $('.supplier').select2({
        ajax: {
            url: "{{ route('ajaxSupplier') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                supplier:params.term,
              };
            },
            results: function(data, page) {
                
            }
        },
    });

    $('.purchase_adminer').select2({
        ajax: {
            url: "{{ route('ajaxUser') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                user:params.term,
              };
            },
            results: function(data, page) {
                
            }
        },
    });


    function quanxuan(model){
        var collid = document.getElementById(model);
        var coll = $("input[class^="+model+"quanxuan]"); 
        if (collid.checked){
            for(var i = 0; i < coll.length; i++){
                coll[i].checked = true;
            }
            /*html ='<div style="margin-left:25px;margin-bottom:15px" class=image_'+model+'><label for="color">上传图片：</label>';
            html+="<div class='upimage'><input name='modelSet["+model+"][image][image0]' type='file'/></div>";
            html+="<div class='upimage'><input name='modelSet["+model+"][image][image1]' type='file'/></div>";
            html+="<div class='upimage'><input name='modelSet["+model+"][image][image2]' type='file'/></div>";
            html+="<div class='upimage'><input name='modelSet["+model+"][image][image3]' type='file'/></div>";
            html+="<div class='upimage'><input name='modelSet["+model+"][image][image4]' type='file'/></div>";
            html+="<div class='upimage'><input name='modelSet["+model+"][image][image5]' type='file'/></div>";
            html+="</div>";
            $("."+model).after(html);*/
        }else{
            for(var i = 0; i < coll.length; i++){
                coll[i].checked = false;
            }
            $(".image_"+model).remove();
        }
    }

$(function () {
    $('#myTab a:first').tab('show');
  })
    $(document).on('change','#catalog_id',function(){
        var catalog_id = $("#catalog_id").val();  
        $.ajax({
            url: "getCatalogProperty",
            data:{catalog_id:catalog_id},
            dataType: "html",
            type:'get',
            success:function(result){
                if(result==0){
                    $(".ajaxinsert").html('');
                }else{
                    $(".ajaxinsert").html(result);  
                }
                
            }
        });       
    });
</script>
@stop