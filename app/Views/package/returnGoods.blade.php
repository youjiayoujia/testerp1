@extends('common.form')
@section('formAction') {{ route('package.processReturnGoods') }} @stop
@section('formBody')
<div class='row'>
    <div class='form-group col-lg-4'>
        <label for="ordernum" class='control-label'>上传文件</label>
        <input type='file' name='returnFile'>
    </div>
    <div class='form-group col-lg-4'>
        <label for="ordernum" class='control-label'>状态变更</label>
        <div class='radio'>
            <input type='radio' class='pass' name='type' value='pass' checked>加库存并变更成已通过
        </div>
        <div class='radio'>
            <input type='radio' class='only' name='type' value='only'>仅加库存
        </div>
    </div>
    <div class='form-group col-lg-4'>
        <label for="ordernum" class='control-label'>库存加到</label>
        <select name='stock_warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class='row'>
    <div class='form-group col-lg-4 tracking'>
        <label for="ordernum" class='control-label'>清空挂号码</label>
        <div class='radio'>
            <input type='radio' name='trackingNo' checked>是
        </div>
        <div class='radio'>
            <input type='radio' name='trackingNo'>否
        </div>
    </div>
    <div class='form-group col-lg-4 logistics'>
        <label for="ordernum" class='control-label'>物流</label>
        <select name='logistics_id' class='form-control'>
            <option value="auto">自动匹配</option>
            @foreach($logisticses as $logistics)
                <option value="{{ $logistics->id }}">{{ $logistics->code }}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group col-lg-4 warehouse'>
        <label for="ordernum" class='control-label'>匹配到仓库</label>
        <select name='from_warehouse_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
</div>
@stop
@section('pageJs')
    <script type="text/javascript">
        $('.pass').click(function(){
            $('.warehouse').show();
            $('.logistics').show();
            $('.tracking').show();
        })

        $('.only').click(function(){
            $('.warehouse').hide();
            $('.logistics').hide();
            $('.tracking').hide();
        })
    </script>
@stop