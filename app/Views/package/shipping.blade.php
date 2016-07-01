@extends('common.detail')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-3'>
        <input type='text' class='form-control search' placeholder='物流id'>
    </div>
    <div class='form-group col-lg-3'>
        <select class='form-control logistics_id' multiple="multiple">
        @foreach($logistics as $logistic)
            <option class='logis' value="{{ $logistic->id }}">{{ $logistic->code }}</option>
        @endforeach
        </select>
    </div>
    <div class='form-group col-lg-6'>
        <div class='buf'></div>
    </div>
</div>
<div class='row'>
    <div class='form-group col-lg-3'>
        <input type='text' name='trackno' class='form-control trackno' placeholder="追踪号">
    </div>
    <div class='form-group col-lg-1'>
        <button type='button' class='btn btn-info shipping form-control'>发货</button>
    </div>
    <div class='form-group col-lg-3 holder'>
    </div>
</div>
<div class='row'>
    <div class='form-group col-lg-3'>
        <input type='text' name='weight' class='form-control weight' placeholder="重量">
    </div>
    <div class='form-group col-lg-3 holder_weight'>
    </div>
</div>
@stop
<script type='text/javascript'>
$(document).ready(function(){
    $('.search').change(function(){
        val = $(this).val();
        if(val) {
            $.get(
                "{{ route('logistics.getLogistics')}}",
                {logistics:val},
                function(result){
                    $('.logistics_id').html('');
                    if(result != 'false') {
                        $('.logistics_id').html(result);
                    }
                }
            );
        }
    });

    $(document).on('click', '.logis', function(){
        log_value = $(this).prop('value');
        log_text = $(this).text();
        str = "<font size='3px' color='green' data-value='"+log_value+"'>"+log_text+" || </font>";
        $('.buf').append(str);
    });

    $(document).on('click', 'font', function(){
        $(this).remove();
    });

    $('.shipping').click(function(){
        trackno = $('.trackno').val();
        weight = $('.weight').val();
        logistic_id = new Array();
        i=0;
        $('font').each(function(){
            logistic_id[i++] = $(this).data('value');
        });
        if(weight && trackno) {
            $.ajax({
                url:"{{ route('package.ajaxShippingExec') }}",
                data:{trackno:trackno, logistic_id:logistic_id, weight:weight},
                dataType:'json', 
                type:'get',
                success:function(result) {
                    $('.holder').text('');
                    $('.holder_weight').text('');
                    if(result == false) {
                        alert('package不存在');
                        return;
                    }
                    if(result == 'logistic_error') {
                        alert('物流不匹配');
                        return;
                    }
                    if(result == true) {
                        $('.holder').text('发送成功');
                        $('.holder_weight').text('重量保存成功');
                        $('.trackno').val('');
                        $('.weight').val('');
                    }
                }
            });
        }
    });
})
</script>