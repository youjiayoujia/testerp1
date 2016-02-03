@extends('common.form')
{{--<script type='text/javascript' src="{{ asset('js/pro_city.js') }}"></script>--}}
@section('formAction') {{ route('logisticsCodeFn') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="col-lg-4">
        <strong>当前物流方式</strong>: {{ $logistic->logistics_type }}
    </div>
    <div class="col-lg-4">
        <strong>当前物流方式简码</strong>: {{ $logistic->short_code }}
    </div>
    <br />
    <br />
    <div class="form-group col-lg-4" style="width:900px;">
        <label for="url" class="control-label">扫码输入(后回车)：</label>
        <input type="hidden" name="logistic_id" value="{{ $logistic->id }}">
        <input type="text" id="scan_input" class="form-control" name="scan_input" value="" style="width:300px;">
    </div>

    <div style="width:900px;height:300px;background: yellow;float:left;clear: left;">

    </div>
@stop

<script type='text/javascript'>
    $(function(){
        $('#scan_input').bind('keypress',function(event){
            if(event.keyCode == "13")
            {
                alert('你输入的内容为：' + $('#dataInput').val());
            }
        });

    });


</script>