@extends('common.detail')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('detailBody')
<div class='row'>
    <div class='form-group col-lg-3'>
        <select class='form-control logistic_id'>
        @foreach($logistics as $logistic)
            <option value={{ $logistic->id }}>{{ $logistic->logistics_type }}</option>
        @endforeach
        </select>
    </div>
    <div class='form-group col-lg-3'>
        <input type='text' name='trackno' class='form-control trackno' placeholder="trackno">
    </div>
    <div class='form-group col-lg-1'>
        <button type='button' class='btn btn-info shipping form-control'>发货</button>
    </div>
    <div class='form-group col-lg-3 holder'>
    </div>
</div>
<div class='row'>
    <div class='form-group col-lg-3'>
        <input type='text' name='weight' class='form-control weight' placeholder="weight">
    </div>
    <div class='form-group col-lg-3 holder_weight'>
    </div>
</div>
@stop
<script type='text/javascript'>
$(document).ready(function(){
    $('.shipping').click(function(){
        trackno = $('.trackno').val();
        weight = $('.weight').val();
        logistic_id = $('.logistic_id').val();
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