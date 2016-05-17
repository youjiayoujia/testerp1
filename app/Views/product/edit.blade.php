@extends('common.form')
@section('formAction') {{ route('product.update', ['id' => $product->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <div class="form-group">
        <label for="catalog_id">分类</label>
        <select id="catalog_id" class="form-control" name="catalog_id" disabled="disabled">
            @foreach($catalogs as $_catalogs)
                <option value="{{ $_catalogs->id }}" {{ $_catalogs->id == $product->catalog_id ? 'selected' : '' }}>{{ $_catalogs->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="ajaxinsert">
        <div class="panel panel-info adjustmargin">
        <div class="panel-heading">选择variation属性:</div>            
                <div class="checkbox panel-body ">
                    <div class="checkbox col-md-2" style="width:auto">
                        <label style="padding-left:0px">
                            {{$product->model}}
                        </label>
                    </div>
                    @foreach($product->catalog->variations as $key=>$getattr)        
                        <div class="checkbox col-md-2 innercheckboxs">{{$getattr->name}}:
                            @foreach($getattr->values as $innervalue)
                                <label>
                                    <input type='checkbox' class='{{$getattr->id}}-{{$innervalue->name}}' name='variations[{{$getattr->id}}][{{$innervalue->id}}]' value='{{$innervalue->name}}' {{ in_array($innervalue->id, $variation_value_id_arr)? 'checked' : '' }}>{{$innervalue->name}}
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="form-group" style="margin-left:25px">
                    <label>编辑默认图片:</label><br>
                    <?php if($product->default_image!=0){ ?>
                    <img src="{{ asset($product->image->path) }}/{{$product->image->name}}" width="120px">
                        <div class='upimage' style="float:right"><input name='replace_image_<?php echo $product->default_image ?>' type='file'/></div>
                        <br>
                        <?php }else{ ?>
                            <div class='upimage' style="float:right"><input name='replace_image_<?php echo $product->default_image ?>' type='file'/></div>
                        <?php } ?>
                    <?php $key=0; ?>
                    @foreach($product->imageAll as $key=>$image)
                        <?php if($product->default_image==$image->id){continue;}else{ ?>
                            <label>编辑图片:</label><br>
                        <?php } ?> 
                        <img src="{{ asset($image->path) }}/{{$image->name}}" width="120px">
                        <div class='upimage' style="float:right"><input name='replace_image_<?php echo $image->id ?>' type='file'/></div>
                        <br>
                    @endforeach
                </div>
                <?php if($key<5){ ?>
                    <div style="margin-left:25px;margin-bottom:15px">
                            <label for="color">上传图片：</label>
                            <?php $j=0;for($i=$key;$i<5;$i++){ ?>
                                <div class='upimage'><input name='image<?php echo $j ?>' type='file'/></div>
                            <?php $j++;} ?>
                    </div>
                <?php } ?>  
        </div>
        <div class="form-group third">
            <label for='set'>feature属性:</label>
            <div class="panel panel-info">
                <div class="checkbox panel-body "><?php $i=0; ?>
                    @foreach($product->catalog->features as $key=>$getfeature)
                        
                        @if($getfeature->type==1)
                            <div class="featurestyle" style="padding-bottom:10px">                           
                                    {{$getfeature->name}} : <input type="text" style="margin-left:15px" id="featuretext{{$getfeature->id}}" value="<?php echo $features_input[$i]['feature_value'];$i++; ?>" name='featureinput[{{$getfeature->id}}]' />
                            </div>
                            
                        @elseif($getfeature->type==2)
                            <div class="radio">{{$getfeature->name}}
                            @foreach($getfeature->values as $value)
                            <label>
                                <input class='{{$getfeature->id}}-{{$value->name}}' {{ in_array($value->id, $features_value_id_arr)? 'checked' : '' }} type='radio' name='features[{{$getfeature->id}}][]' value='{{$value->name}}'>{{$value->name}}
                            </label>
                            @endforeach
                            </div>
                        @else($getfeature->type==3)
                            <div class="checkbox">{{$getfeature->name}}
                            @foreach($getfeature->values as $value)
                            <label>
                                <input class='{{$getfeature->id}}-{{$value->name}}' {{ in_array($value->id, $features_value_id_arr)? 'checked' : '' }} type='checkbox' name='features[{{$getfeature->id}}][]' value='{{$value->name}}'>{{$value->name}}
                            </label>
                            @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div> 
    </div>
    <div class='row'>
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
            <label for="color">主供应商货号</label>
            <input class="form-control" id="supplier_sku" placeholder="主供应商货号" name='supplier_sku' value="{{ old('supplier_sku') ?  old('supplier_sku') : $product->supplier_sku }}">
        </div>
    </div>

    <div class='row'>  
        <div class="form-group col-md-3"><label for="color">辅供应商</label>
            <select  class="form-control" name="second_supplier_id">
                <option value="0"></option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id}}" {{ $supplier->id == $product->second_supplier_id ? 'selected' : '' }} >{{$supplier->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="color">辅供应商货号</label>
            <input class="form-control" id="second_supplier_sku" placeholder="主供应商货号" name='second_supplier_sku' value="{{ old('second_supplier_sku') ?  old('second_supplier_sku') : $product->second_supplier_sku }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购链接</label>
            <input class="form-control" id="purchase_url" placeholder="采购链接" name='purchase_url' value="{{ old('purchase_url') ?  old('purchase_url') : $product->purchase_url }}">
        </div>
        <div class="form-group col-md-1">
            <label for="size">采购价(RMB)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_price" placeholder="采购价" name='purchase_price' value="{{ old('purchase_price') ?  old('purchase_price') : $product->purchase_price }}">
        </div>
        <div class="form-group col-md-1">
            <label for="color">采购物流费(RMB)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_carriage" placeholder="采购物流费" name='purchase_carriage' value="{{ old('purchase_carriage') ?  old('purchase_carriage') : $product->purchase_carriage }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">采购天数</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="purchase_day" placeholder="采购天数" name='purchase_day' value="{{ old('purchase_day') ?  old('purchase_day') : $product->purchase_day }}">
        </div>
        <div class="form-group col-md-1">
            <label for="size">尺寸类型</label><small class="text-danger glyphicon glyphicon-asterisk"></small>

            <select id="supplier_id" class="form-control" name="product_size">     
                <option value="大" {{ $product->product_size == '大' ? 'selected' : '' }}>大</option>
                <option value="中" {{ $product->product_size == '中' ? 'selected' : '' }}>中</option>
                <option value="小" {{ $product->product_size == '小' ? 'selected' : '' }}>小</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="color">产品包装尺寸（cm）(长*宽*高)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>(长*宽*高)
            <input class="form-control" id="package_size" placeholder="产品包装尺寸" name='package_size' value="{{ old('package_size') ?  old('package_size') : $product->package_size }}">
        </div>
        <div class="form-group col-md-1">
            <label for="size">产品重量(kg)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight" placeholder="产品重量" name='weight' value="{{ old('weight') ?  old('weight') : $product->weight }}">
        </div>
        <div class="form-group col-md-1">
            <label for="color">更新人</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="upload_user" placeholder="上传人" name='upload_user' value="{{ old('upload_user') ?  old('upload_user') : $product->upload_user }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-md-12" style="padding-top:26px">
            <label for="color">物流限制</label>
            @foreach($logisticsLimit as $carriage_limit)
                    <label>
                        <input type='checkbox' name='carriage_limit_arr[]' value='{{$carriage_limit->id}}' {{ in_array($carriage_limit->id, explode(',',$product->carriage_limit))? 'checked' : '' }} >{{$carriage_limit->name}}
                    </label>
            @endforeach
        </div>
        <div class="form-group col-md-12" style="padding-top:26px">
            <label for="color">包装限制</label>
            @foreach($wrapLimit as $wrap_limit)
                    <label>
                        <input type='checkbox' name='package_limit_arr[]' value='{{$wrap_limit->id}}' {{ in_array($wrap_limit->id, explode(',',$product->package_limit))? 'checked' : '' }} >{{$wrap_limit->name}}
                    </label>
            @endforeach
        </div>
        
        <div class="form-group col-md-3">
            <label for="color">尺寸描述</label>
            <input class="form-control" id="size_description" placeholder="尺寸描述" name='size_description' value="{{ old('size_description') ?  old('size_description') : $product->size_description }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">描述</label>
            <input class="form-control" id="description" placeholder="备注" name='description' value="{{ old('description') ?  old('description') : $product->description }}">
        </div>
        <div class="form-group col-md-3">
            <label for="color">备注</label>
            <input class="form-control" id="remark" placeholder="备注" name='remark' value="{{ old('remark') ?  old('remark') : $product->remark }}">
        </div>
    </div>
@stop