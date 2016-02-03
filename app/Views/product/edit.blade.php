@extends('common.form')
@section('formAction') {{ route('product.update', ['id' => $product->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
    <div class="form-group">
        <label for="catalog_id">分类</label>
        <select id="catalog_id" class="form-control" name="catalog_id">
            @foreach($catalogs as $_catalogs)
                <option value="{{ $_catalogs->id }}" {{ $_catalogs->id == $product->catalog_id ? 'selected' : '' }}>{{ $_catalogs->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="ajaxinsert">
        <div class="panel panel-info adjustmargin">
        <div class="panel-heading">选择attribute属性:</div>            
                <div class="checkbox panel-body ">
                    <div class="checkbox col-md-2">
                        <label>
                            {{$product->model}}
                        </label>
                    </div>
                    @foreach($product->catalog->attributes as $key=>$getattr)        
                        <div class="checkbox col-md-2">{{$getattr->name}}:
                            @foreach($getattr->values as $innervalue)
                                <label>
                                    <input type='checkbox' class='{{$getattr->id}}-{{$innervalue->name}}' name='attributes[{{$getattr->id}}][]' value='{{$innervalue->name}}'>{{$innervalue->name}}
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div style="margin-left:25px;margin-bottom:15px">
                        <label for="color">上传图片：</label>
                        <div class='upimage'><input name='image0' type='file'/></div>
                        <div class='upimage'><input name='image1' type='file'/></div>
                        <div class='upimage'><input name='image2' type='file'/></div>
                        <div class='upimage'><input name='image3' type='file'/></div>
                        <div class='upimage'><input name='image4' type='file'/></div>
                        <div class='upimage'><input name='image5' type='file'/></div>
                </div>              
        </div>
        <div class="form-group third">
            <label for='set'>feature属性:</label>
            <div class="panel panel-info">
                <div class="checkbox panel-body ">
                    @foreach($product->catalog->features as $key=>$getfeature)
                        @if($getfeature->type==1)
                            <div class="featurestyle" style="padding-bottom:10px">                           
                                    {{$getfeature->name}} : <input type="text" style="margin-left:15px" id="featuretext{{$getfeature->id}}" value="" name='featureinput[{{$getfeature->id}}]' />
                            </div>
                        @elseif($getfeature->type==2)
                            <div class="radio">{{$getfeature->name}}
                            @foreach($getfeature->values as $value)
                            <label>
                                <input class='{{$getfeature->id}}-{{$value->name}}' type='radio' name='features[{{$getfeature->id}}][]' value='{{$value->name}}'>{{$value->name}}
                            </label>
                            @endforeach
                            </div>
                        @else($getfeature->type==3)
                            <div class="checkbox">{{$getfeature->name}}
                            @foreach($getfeature->values as $value)
                            <label>
                                <input class='{{$getfeature->id}}-{{$value->name}}' type='checkbox' name='features[{{$getfeature->id}}][]' value='{{$value->name}}'>{{$value->name}}
                            </label>
                            @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div> 
    </div>
    <div class="form-group col-md-3">
        <label for="size">产品name</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="name" placeholder="产品name" name='name' value="{{ old('name') ?  old('name') : $product->name }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">产品中文名</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="c_name" placeholder="产品中文名" name='c_name' value="{{ old('c_name') ?  old('c_name') : $product->c_name }}">
    </div>
    <div class="form-group col-md-3">
        <label for="size">主供应商</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select id="supplier_id" class="form-control" name="supplier_id">
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $product->supplier_id ? 'selected' : '' }}>{{$supplier->name}}</option>
            @endforeach
        </select>
    </div>   
    <div class="form-group col-md-3">
        <label for="size">供应商信息</label>
        <input class="form-control" id="supplier_info" placeholder="供应商信息" name='supplier_info' value="{{ old('supplier_info') ?  old('supplier_info') : $product->supplier_info }}">
    </div>        
    <div class="form-group col-md-3"><label for="color">辅供应商</label>
        <select  class="form-control" name="second_supplier_id_arr[]">
            <option value="0"></option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $second_supplier_id[0] ? 'selected' : '' }} >{{$supplier->name}}</option>
            @endforeach
        </select>
    </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $second_supplier_id[1] ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $second_supplier_id[2] ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
                </select>
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}" {{ $supplier->id == $second_supplier_id[3] ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
            </select>          
        </div>
    <div class="form-group col-md-3">
        <label for="color">采购链接</label>
        <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') ?  old('purchase_url') : $product->purchase_url }}">
    </div>
    <div class="form-group col-md-1">
        <label for="size">采购价</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') ?  old('purchase_price') : $product->purchase_price }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">采购物流费</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') ?  old('purchase_carriage') : $product->purchase_carriage }}">
    </div>
    <div class="form-group col-md-1">
        <label for="size">产品尺寸</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="product_size" placeholder="产品尺寸" name='product_size' value="{{ old('product_size') ?  old('product_size') : $product->product_size }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">产品包装尺寸</label>
        <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') ?  old('package_size') : $product->package_size }}">
    </div>
    <div class="form-group col-md-1">
        <label for="size">产品重量</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="weight" placeholder="产品重量" name='weight' value="{{ old('weight') ?  old('weight') : $product->weight }}">
    </div>
    <div class="form-group col-md-1">
        <label for="color">更新人</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="upload_user" placeholder="上传人" name='upload_user' value="{{ old('upload_user') ?  old('upload_user') : $product->upload_user }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">物流限制</label>
        <input class="form-control" id="carriage_limit" placeholder="物流限制" name='carriage_limit' value="{{ old('carriage_limit') ?  old('carriage_limit') : $product->carriage_limit }}">
    </div>
    <div class="form-group col-md-3">
        <label for="size">物流限制1</label>
        <input class="form-control" id="carriage_limit_1" placeholder="物流限制1" name='carriage_limit_1' value="{{ old('carriage_limit_1') ?  old('carriage_limit_1') : $product->carriage_limit_1 }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">包装限制</label>
        <input class="form-control" id="package_limit" placeholder="包装限制" name='package_limit' value="{{ old('package_limit') ?  old('package_limit') : $product->package_limit }}">
    </div>
    <div class="form-group col-md-3">
        <label for="size">包装限制1</label>
        <input class="form-control" id="package_limit_1" placeholder="包装限制1" name='package_limit_1' value="{{ old('package_limit_1') ?  old('package_limit_1') : $product->package_limit_1 }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">备注</label>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $product->remark }}">
    </div>  
    <input type='hidden' value='PUT' name="_method">

@stop
@section('pageJs')
<script type="text/javascript">
    $(document).ready(function() { 
        var attributes = <?php echo $attributes; ?>;
        for(i=0;i<attributes.length;i++){
            var key = attributes[i].attribute_id+'-'+attributes[i].attribute_value;
            $("."+key).attr("checked", true);
        }
        var features = <?php echo $features; ?>;
        
        for(i=0;i<features.length;i++){
            var key = features[i].feature_id+'-'+features[i].feature_value;
            $("."+key).attr("checked", true);
            $("#featuretext"+features[i].feature_id).val(features[i].feature_value);
        }
    }); 

    $(document).on('change','#catalog_id',function(){
        var catalog_id = $("#catalog_id").val();
        var product_id = {{$product->id}};
        $.ajax({
            url: "../getCatalogProperty",
            data:{catalog_id:catalog_id,product_id:product_id},
            dataType: "json",
            type:'get',
            success:function(result){
                var html = '<div class="panel panel-info adjustmargin">';
                html += '<div class="panel-heading ">选择attribute属性:</div>';

                html += '<div class="checkbox panel-body "><div class="checkbox col-md-2">';
                html += '<label>{{$product->model}}</label></div>';
                for(k=0;k<result['attributes'].length;k++){
                    html+= '<div class="checkbox col-md-2">'+result['attributes'][k]['name']+':';
                    for(m=0;m<result['attributes'][k]['value'].length;m++){
                        html+= '<label style="padding-left:25px"><input type="checkbox" name="" value="'+result["attributes"][k]["value"][m]+'">'+result["attributes"][k]["value"][m]+'</label>';
                    }
                    html+='</div>';
                }
                html += '</div>';
                html += '<div style="margin-left:25px;margin-bottom:15px"><label for="color">上传图片：</label><div class="upimage"><input name="image0" type="file"/></div><div class="upimage"><input name="image1" type="file"/></div><div class="upimage"><input name="image2" type="file"/></div><div class="upimage"><input name="image3" type="file"/></div><div class="upimage"><input name="image4" type="file"/></div><div class="upimage"><input name="image5" type="file"/></div>';
                html += '</div><hr width="98%" style="border:0.5px solid #d9edf7">';

                html +='</div>';
                html +='<div class="form-group third"><label for="set">feature属性:</label><div class="panel panel-info"><div class="checkbox panel-body ">';
                for(var n=0;n<result['features'].length;n++){
                    switch(result['features'][n]['type'])
                    {
                    case 1:
                        html += '<div>';
                        html+='<label><input type="checkbox" name="featureinput['+result['features'][n]['feature_id']+']" value="'+result['features'][n]['name']+'">'+result['features'][n]['name']+'</label>';
                        html +='</div>';
                        break;
                    case 2:
                        html +='<div class="radio">'+result['features'][n]['name'];
                        for(var p=0;p<result['features'][n]['value'].length;p++){
                            html+='<label style="padding-left:25px"><input type="radio" name="featureradio['+result['features'][n]['feature_id']+'][]" value="'+result['features'][n]['value'][p]+'">'+result['features'][n]['value'][p]+'</label>';
                        }
                        html +='</div>';                         
                        break;
                    case 3:
                        html +='<div class="checkbox">'+result['features'][n]['name'];
                        for(var p=0;p<result['features'][n]['value'].length;p++){
                            html+='<label style="padding-left:25px"><input type="checkbox" name="featurecheckbox['+result['features'][n]['feature_id']+'][]" value="'+result['features'][n]['value'][p]+'">'+result['features'][n]['value'][p]+'</label>';
                        }
                        html +='</div>';
                        break;              
                    }
                }
                html +='</div>';
                html +='</div>';
                html +='</div>';
                $(".ajaxinsert").html(html);
            }
        });       
    });
</script>
@stop