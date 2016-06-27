@extends('common.form')
@section('formAction')  {{ route('batchUpdate') }} @stop
@section('formBody')
    <input type='hidden' value='PUT' name="_method">
    <input type='hidden' value='{{$item_ids}}' name="item_ids">
    <div class="form-group">
        <label for="sku">待编辑的sku：</label>
    </div>
    <div class="row">
        
        @foreach($skus as $sku)
            <div class="form-group col-md-1">
                <label for="sku">{{$sku->sku}}</label>
            </div>
        @endforeach
    </div>

    <div class="row">
        <?php 
            switch ($param) {
                case 'status':
                     ?>
                     <div class="form-group col-md-3">
                        <label for="size">状态</label>
                        <select id="status" class="form-control" name="status">
                            <option value="sellWaiting">待售</option>
                            <option value="selling">在售</option>
                            <option value="saleOutStopping">卖完下架</option>
                            <option value="stopping">停产</option>
                            <option value="trySale">试销</option>
                            <option value="unSellTemp">货源待定</option>
                        </select>
                    </div>
                <?php 
                    break;
                
                case 'weight':
                ?>
                <div class="form-group col-md-3">
                    <label for="color">重量</label>
                    <input class="form-control" id="weight" placeholder="重量" name='weight' value="{{old('weight')}}">
                </div>
                <?php 
                    break;

                case 'purchase_price':
                ?>
                
                    <div class="form-group col-md-3">
                        <label for="color">参考成本</label>
                        <input class="form-control" id="cost" placeholder="参考成本" name='cost' value="{{old('cost')}}">
                    </div>
                
                <?php
                    break;

                case 'package_size':
                ?>
                
                    <div class="form-group col-md-3">
                        <label for="color">包装后体积(长*宽*高)</label>
                        <input class="form-control" id="package_size" placeholder="" name='package_size' value="{{old('package_size')}}">
                    </div>
                
                <?php
                    break;
            } 
        ?>
    </div>

    
@stop