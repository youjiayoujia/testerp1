@extends('common.form')
@section('formAction') {{ route('EditProduct.update', ['id' => $model->id]) }} @stop
@section('formAttributes') enctype="multipart/form-data" @stop
@section('formBody')
<?php 
    $check = $model->productEnglishValue;
    $unedit_reason = '';
    $market_usd_price = '';
    $cost_usd_price = '';
    $sale_usd_price = '';
    if(!empty($check)){
        $unedit_reason = $check->unedit_reason;
        $market_usd_price = $check->market_usd_price;
        $cost_usd_price = $check->cost_usd_price;
        $sale_usd_price = $check->sale_usd_price;
    } 
?>
<input type='hidden' value='PUT' name="_method">
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>泽尚SKU</td>
            <td></td>
        </tr>
        <tr>
         <td>status:picked</td>
         <td></td>
        </tr>
        <tr>
        <td>备注:{{$model->remark}}</td>
         <td>
            <label style="width:80px">主表:英文名: </label>
            <textarea class="form-control form55" style="width:300px;" id="s_en_name" value="" name="s_en_name"></textarea>
            <br><label style="width:80px"></label>
        </td>
        </tr>
        <tr>
            <td><label>产品中文名: </label>{{$model->c_name}}</td>
            <td><label>主表:中文名: </label><input type="text" class="form-control form55" style="width:300px;" id="s_cn_name" value="" name="s_cn_name"></td>
        </tr>
        <tr>
            <td><label>图片备注: </label></td>
            <td><lable>store:</lable>
                <input type="text" class="form-control form55" style="width:300px;" id="" value="" name="store">
            </td>
        </tr>
        <tr>
            <td>
                <label>图片URL: </label>
                <?php if(isset($model->image->name)){ ?>
                    <a target='_blank' href='{{ asset($model->image->path) }}/{{$model->image->name}}'>{{ asset($model->image->path) }}/{{$model->image->name}}</a>
                <?php }
                else{ ?>
                无图片
                <?php } ?>
            </td>
            <td>
                <?php if(isset($model->image->name)){ ?>
                <img src="{{ asset($model->image->path) }}/{{$model->image->name}}" width="150px" >
                <?php }else{ ?>
                    无图片
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td><label>颜色: </label></td>
            <td>
                <label>Filter_attributes: </label>
                <br>
                <textarea class="vLargeTextField" cols="50" id="s_filter_attributes" name="s_filter_attributes" rows="3"></textarea>
            </td>
        </tr>
        <tr>
            <td><label>尺码: </label></td>
            <td></td>
        </tr>
        <tr>
            <td><label>尺码描述: </label></td>
            <td>
                <label>主表:简短描述(brief): </label>
                <br>
                <textarea class="vLargeTextField" cols="50" id="s_brief" name="s_brief" rows="3"></textarea>
            </td>
        </tr>
        <tr>
            <td><label>材质: </label></td>
            <td></td>
        </tr>
        <tr>
            <td><label>是否有弹性: </label><br>
                <label>拉链: </label>
            </td>

            <td></td>
        </tr>
        <tr>
            <td><label>配件说明: </label>{{$model->description}}</td>
            <td>
                <label>主表:描述(description): </label>
                <br>
                <textarea class="vLargeTextField" cols="50" id="s_description" name="s_description" rows="3"></textarea>
            </td>
        </tr>
        <tr>
            <td><label>净重: </label>{{$model->weight}} kg</td>
            <td>
                <label>主表:重量: </label>
                <input type="text" class="form-control form55" id="s_weight" value="0.0" name="s_weight">
            </td>
        </tr>
        <tr>
            <td><label>主供货商: </label>{{$model->supplier->name}}</td>
            <td><label>factory:</label>{{$model->supplier->name}}</td>
        </tr>
        <tr>
            <td><label>供货商地址: </label><a target='_blank' href='{{$model->purchase_url}}'>{{$model->purchase_url}}</a></td>
            <td><label>taobao_url: </label><a target='_blank' href='{{$model->purchase_url}}'>{{$model->purchase_url}}</a></td>
        </tr>
        <tr>
            <td><label>供应商货号: </label>{{$model->supplier_sku}}</td>
            <td>
                <label>supplier_sku: </label>{{$model->supplier_sku}}
            </td>
        </tr>
        <tr>
            <td><label>择尚拿货价(RMB): </label>{{$model->purchase_price}}</td>
            <td>
                <label>主表:销售价美元: </label><input type="text" class="form-control form55" name="sale_usd_price" id="sale_usd_price" value="{{ old('sale_usd_price') ?  old('sale_usd_price') : $sale_usd_price }}">
            </td>
        </tr>
        <tr>
            <td><label>参考现货数量: </label></td>
            <td>
                <label>主表:市场价美元: </label>
                <input type="text" class="form-control form55" id="market_usd_price" value="{{ old('market_usd_price') ?  old('market_usd_price') : $market_usd_price }}" name="market_usd_price">
            </td>
        </tr>
        <tr>
            <td><label>快递费用(RMB): </label>{{$model->purchase_carriage}}</td>
            <td>
                <label>主表:成本价美元: </label><span id="p_cost" style="color:red;"></span>
                <input type="text" class="form-control form55" id="cost_usd_price" value="{{ old('cost_usd_price') ?  old('cost_usd_price') : $cost_usd_price }}" name="cost_usd_price">
            </td>
        </tr>
        <tr>
            <td><label>是否透明: </label></td>
            <td></td>
        </tr>
        <tr>
            <td><label>信息录入员: </label></td>
            <td>
                <label>备注不编辑原因: </label><input type="text" class="form-control form55" id="unedit_reason" value="{{ old('unedit_reason') ?  old('unedit_reason') : $unedit_reason }}" name="unedit_reason">
            </td>
        </tr>
        <tr>
            <td><label>选款人ID: </label>{{$model->upload_user}}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"><label>上传时间: </label>{{$model->created_at}}</td>
            
        </tr>

    </tbody>
</table>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success" name='edit' value='0'>保存</button>
    <button type="submit" class="btn btn-success" name='edit' value='1'>审核</button>
    <button type="reset" class="btn btn-default">取消</button>
@show{{-- 表单按钮 --}}