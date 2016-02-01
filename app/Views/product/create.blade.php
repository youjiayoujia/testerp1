@extends('common.form')
@section('formAction') {{ route('product.store') }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')

    <div class="form-group">
        <label for="catalog_id">分类</label>
        <select id="catalog_id" class="form-control" name="catalog_id">
            @foreach($catalogs as $_catalogs)
                <option value="{{ $_catalogs->id }}">{{ $_catalogs->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="ajaxinsert">
        <div class="panel panel-info adjustmargin">

            <div class="panel-heading">勾选model及对应attribute属性:</div>
            @foreach($models as $model)
                <div class="checkbox panel-body ">
                    <div class="checkbox col-md-2">
                        <label>
                            <input type='checkbox' id="{{$model}}" onclick="quanxuan('{{$model}}')" name='modelSet[{{$model}}][model]' value='{{$model}}'>{{$model}}
                        </label>
                    </div>
                    @foreach($catalogs[0]->attributes as $key=>$getattr)        
                        <div class="checkbox col-md-2">{{$getattr->name}}:
                            @foreach($getattr->values as $innervalue)
                                <label>
                                    <input type='checkbox' class="{{$model}}quanxuan" name='modelSet[{{$model}}][attributes][{{$getattr->name}}][]' value='{{$innervalue->name}}'>{{$innervalue->name}}
                                </label>
                            @endforeach
                        </div>
                        @endforeach
                </div>

                <div style="margin-left:25px;margin-bottom:15px">
                        <label for="color">上传图片：</label>
                        <div class='upimage'><input name='modelSet[{{$model}}][image][image0]' type='file'/></div>
                        <div class='upimage'><input name='modelSet[{{$model}}][image][image1]' type='file'/></div>
                        <div class='upimage'><input name='modelSet[{{$model}}][image][image2]' type='file'/></div>
                        <div class='upimage'><input name='modelSet[{{$model}}][image][image3]' type='file'/></div>
                        <div class='upimage'><input name='modelSet[{{$model}}][image][image4]' type='file'/></div>
                        <div class='upimage'><input name='modelSet[{{$model}}][image][image5]' type='file'/></div>
                </div>  
            <hr width="98%" style="border:0.5px solid #d9edf7">
            @endforeach
        </div>

        <div class="form-group third">
            <label for='set'>feature属性:</label>
            <div class="panel panel-info">
                <div class="checkbox panel-body ">
                    @foreach($catalogs[0]->features as $key=>$getfeature)
                        @if($getfeature->type==1)
                            <div>
                            
                            <label>
                                
                                <input type='checkbox' name='featureinput[{{$getfeature->id}}]' value='{{$getfeature->name}}'>    {{$getfeature->name}}
                            </label>
                            
                            </div>
                        @elseif($getfeature->type==2)
                            <div class="radio">{{$getfeature->name}}
                            @foreach($getfeature->values as $value)
                            <label>
                                <input type='radio' name='featureradio[{{$getfeature->id}}][]' value='{{$value->name}}'>{{$value->name}}
                            </label>
                            @endforeach
                            </div>
                        @else($getfeature->type==3)
                            <div class="checkbox">{{$getfeature->name}}
                            @foreach($getfeature->values as $value)
                            <label>
                                <input type='checkbox' name='featurecheckbox[{{$getfeature->id}}][]' value='{{$value->name}}'>{{$value->name}}
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
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}">{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}">{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id}}">{{$supplier->name}}</option>
                @endforeach
                </select>
        </div>
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id_arr[]">
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
        <input class="form-control" id="carriage_limit" placeholder="物流限制" name='carriage_limit' value="{{ old('carriage_limit') }}">
    </div>
            <div class="form-group col-md-3">
        <label for="size">物流限制1</label>
        <input class="form-control" id="carriage_limit_1" placeholder="物流限制1" name='carriage_limit_1' value="{{ old('carriage_limit_1') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">包装限制</label>
        <input class="form-control" id="package_limit" placeholder="包装限制" name='package_limit' value="{{ old('package_limit') }}">
    </div>
            <div class="form-group col-md-3">
        <label for="size">包装限制1</label>
        <input class="form-control" id="package_limit_1" placeholder="包装限制1" name='package_limit_1' value="{{ old('package_limit_1') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="color">备注</label>
        <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') }}">
    </div>
    
@stop

@section('pageJs')
    <script type="text/javascript">
    $(document).ready(function(){
        //alert(23);
    })

        $(document).on('click','.quanxuan',function(){
            var model = $(this).val();
            if($("input[name^='modelSet["+model+"]'").attr("checked")=='checked'){
                $("input[name^='modelSet["+model+"]'").attr("checked", false);
            }else{
                $("input[name^='modelSet["+model+"]'").attr("checked", true);
            }
            
            //alert(model);
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
                dataType: "json",
                type:'get',
                success:function(result){
                    var html = '<div class="panel panel-info adjustmargin">';
                    html += '<div class="panel-heading ">勾选model及对应attribute属性:</div>';
                    for(var i =0;i<result['models'].length;i++){
                            html += '<div class="checkbox panel-body "><div class="checkbox col-md-2">';
                            html += '<label><input type="checkbox" id="'+result['models'][i]+'" onclick="quanxuan(\''+result['models'][i]+'\')" name="modelSet['+result['models'][i]+'][model]" value="'+result['models'][i]+'">'+result['models'][i]+'</label></div>';
                            for(k=0;k<result['attributes'].length;k++){
                                html+= '<div class="checkbox col-md-2">'+result['attributes'][k]['name']+':';
                                for(m=0;m<result['attributes'][k]['value'].length;m++){
                                    html+= '<label style="padding-left:25px"><input type="checkbox" class="'+result['models'][i]+'quanxuan" name="modelSet['+result['models'][i]+'][attributes]['+result["attributes"][k]["name"]+'][]" value="'+result["attributes"][k]["value"][m]+'">'+result["attributes"][k]["value"][m]+'</label>';
                                }
                                html+='</div>';
                            }
                            html += '</div>';
                            html += '<div style="margin-left:25px;margin-bottom:15px"><label for="color">上传图片：</label><div class="upimage"><input name="modelSet['+result['models'][i]+'][image][image0]" type="file"/></div><div class="upimage"><input name="modelSet['+result['models'][i]+'][image][image1]" type="file"/></div><div class="upimage"><input name="modelSet['+result['models'][i]+'][image][image2]" type="file"/></div><div class="upimage"><input name="modelSet['+result['models'][i]+'][image][image3]" type="file"/></div><div class="upimage"><input name="modelSet['+result['models'][i]+'][image][image4]" type="file"/></div><div class="upimage"><input name="modelSet['+result['models'][i]+'][image][image5]" type="file"/></div>';
                            html += '</div><hr width="98%" style="border:0.5px solid #d9edf7">';
                    }
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