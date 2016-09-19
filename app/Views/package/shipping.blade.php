@extends('common.detail')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-3'>
        <label>平邮渠道是否称重</label>
        <select class='takeweight form-control'>
            <option value='1'>称重</option>
            <option value='0'>不称重</option>
        </select>
    </div>
</div>
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
    <div class='form-group col-lg-1'>
        <a class='btn btn-info print_blade form-control'>打印装袋面单</a>
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
<iframe src='' id='iframe_print' style='display:none'></iframe>
@stop
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.print_blade', function () {
        trackno = $('.trackno').val();
        if(trackno) {
            src = "{{ route('package.bagInfo') }}/?trackno=" + trackno;
            $('#iframe_print').attr('src', src);
            $('#iframe_print').load(function () {
                $('#iframe_print')[0].contentWindow.focus();
                $('#iframe_print')[0].contentWindow.print();
            });
        }
        
    });

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

    $('.takeweight').change(function(){
        if($(this).val() == '1') {
            $('.weight').prop('disabled', false);
        } else {
            $('.weight').prop('disabled', true);
        }
    })

    $(document).on('click', '.logis', function(){
        log_value = $(this).prop('value');
        log_text = $(this).text();
        str = "<font size='3px' color='green' data-value='"+log_value+"'>"+log_text+" || </font>";
        $('.buf').append(str);
    });

    $(document).on('click', 'font', function(){
        $(this).remove();
    });

    $(document).on('keypress', function(event){
        if(event.keyCode == '13') {
            if($('.weight').is(':focus')) {
                $('.trackno').val('');
                $('.trackno').focus();
                return true;
            }
            if($('.trackno').is(':focus')) {
                $('.shipping').click();
                return true;
            }
        }
    })

    $('.shipping').click(function(){
        $flag = 1;
        trackno = $('.trackno').val();
        weight = $('.weight').val();
        logistic_id = new Array();
        i=0;
        $('font').each(function(){
            logistic_id[i++] = $(this).data('value');
        });
        if($('.takeweight').val() == '0') {
            $flag = 2;
            weight = 0;
        }
        if($flag == '1' && weight && trackno || $flag == '2' && trackno) {
            $.ajax({
                url:"{{ route('package.ajaxShippingExec') }}",
                data:{trackno:trackno, logistic_id:logistic_id, weight:weight},
                dataType:'json', 
                type:'get',
                success:function(result) {
                    $('.holder').text('');
                    $('.holder_weight').text('');
                    if(result == 'error') {
                        $('.holder_weight').text('package不存在');
                        $('.trackno').val('');
                        $('.weight').val('');
                        if($('.takeweight').val() == '1') {
                            $('.weight').focus();
                        } else {
                            $('.trackno').focus();
                        }
                        $('.holder').html("<span class='glyphicon glyphicon-remove'></span>");
                        return false;
                    }
                    if(result == 'logistic_error') {
                        $('.holder_weight').text('物流不匹配');
                        $('.trackno').val('');
                        $('.weight').val('');
                        if($('.takeweight').val() == '1') {
                            $('.weight').focus();
                        } else {
                            $('.trackno').focus();
                        }
                        $('.holder').html("<span class='glyphicon glyphicon-remove'></span>");
                        return false;
                    }
                    if(result == 'unhold') {
                        $('.holder_weight').text('unhold时数据有误');
                        $('.trackno').val('');
                        $('.weight').val('');
                        if($('.takeweight').val() == '1') {
                            $('.weight').focus();
                        } else {
                            $('.trackno').focus();
                        }
                        $('.holder').html("<span class='glyphicon glyphicon-remove'></span>");
                        return false;
                    }
                    if(result == "success") {
                        $('.holder').html("<span class='glyphicon glyphicon-ok'></span>");
                        $('.holder_weight').text('重量保存成功');
                        $('.trackno').val('');
                        $('.weight').val('');
                        if($('.takeweight').val() == '1') {
                            $('.weight').focus();
                        } else {
                            $('.trackno').focus();
                        }
                    }
                }
            });
        }
    });
})
</script>