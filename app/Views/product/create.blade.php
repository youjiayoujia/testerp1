@extends('common.form')
@section('formAction') {{ route('product.store') }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')

    <div class="form-group">
        <label for="catalog_id">分类</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select id="catalog_id" class="form-control" name="catalog_id">
            <option value="">选择分类</option>
            @foreach($catalogs as $_catalogs)
                <option value="{{ $_catalogs->id }}">{{ $_catalogs->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="ajaxinsert">
 
    </div>
    <div class="form-group col-md-3">
        <label for="size">产品name</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="product_name" placeholder="产品name" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">产品中文名</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') }}">
    </div>
        <div class="form-group col-md-3">
        <label for="size">主供应商</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select id="supplier_id" class="form-control" name="supplier_id">
            <option value=""></option>
            @foreach($suppliers as $supplier)
            <option value="{{ $supplier->id}}" {{ $supplier->id == old('supplier_id') ? 'selected' : '' }} >{{$supplier->name}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group col-md-2">
        <label for="size">供应商信息</label>
        <input class="form-control" id="supplier_info" placeholder="供应商信息" name='supplier_info' value="{{ old('supplier_info') }}">
    </div>

    <div class="form-group col-md-1">
        <label for="size">供应商货号</label>
        <input class="form-control" id="supplier_sku" placeholder="供应商货号" name='supplier_sku' value="{{ old('supplier_sku') }}">
    </div>

    <div class="form-group col-md-3"><label for="color">辅供应商</label>
        <select  class="form-control" name="second_supplier_id">
            <option value="0"></option>
            @foreach($suppliers as $supplier)
            <option value="{{ $supplier->id}}">{{$supplier->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
        <label for="color">采购链接</label>
        <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') }}">
    </div>
        <div class="form-group col-md-1">
        <label for="size">采购价</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">采购物流费</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') }}">
    </div>
        <div class="form-group col-md-1">
        <label for="size">产品尺寸</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="product_size" placeholder="产品尺寸" name='product_size' value="{{ old('product_size') }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">产品包装尺寸</label>
        <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') }}">
    </div>
        <div class="form-group col-md-1">
        <label for="size">产品重量</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="weight" placeholder="产品重量" name='weight' value="{{ old('weight') }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">上传人</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="upload_user" placeholder="上传人" name='upload_user' value="{{ old('upload_user') }}">
    </div>

    <div class="form-group col-md-3">
        <label for="color">物流限制</label>
        <select  class="form-control" name="carriage_limit">
            <option value=""></option>
            @foreach(config('product.carriage_limit') as $carriage_key=>$carriage_limit)
                <option value="{{ $carriage_limit }}">{{$carriage_limit}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="size">物流限制1</label>
        <select  class="form-control" name="carriage_limit_1">
            <option value=""></option>
            @foreach(config('product.carriage_limit') as $carriage_key=>$carriage_limit)
                <option value="{{ $carriage_limit }}">{{$carriage_limit}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="color">包装限制</label>
        <select  class="form-control" name="package_limit">
            <option value=""></option>
            @foreach(config('product.package_limit') as $package_key=>$package_limit)
                <option value="{{ $package_limit }}">{{$package_limit}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="size">包装限制1</label>
        <select  class="form-control" name="package_limit_1">
            <option value=""></option>
            @foreach(config('product.package_limit') as $package_key=>$package_limit)
                <option value="{{ $package_limit }}">{{$package_limit}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="color">备注</label>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') }}">
    </div>

@stop

@section('pageJs')
    <script type="text/javascript">
        $(document).on('click','.quanxuan',function(){
            var model = $(this).val();
            if($("input[name^='modelSet["+model+"]'").attr("checked")=='checked'){
                $("input[name^='modelSet["+model+"]'").attr("checked", false);
            }else{
                $("input[name^='modelSet["+model+"]'").attr("checked", true);
            }
        })

        function quanxuan(id){
            var collid = document.getElementById(id);
            var coll = $("input[class^="+id+"quanxuan]"); 
            if (collid.checked){
               for(var i = 0; i < coll.length; i++)
                   coll[i].checked = true;
            }else{
               for(var i = 0; i < coll.length; i++)
                   coll[i].checked = false;
            }
        }

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