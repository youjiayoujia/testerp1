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
@stop
<script type='text/javascript'>
$(document).ready(function(){
    $('.shipping').click(function(){
        trackno = $('.trackno').val();
        logistic_id = $('.logistic_id').val();
        $.ajax({
            url:"{{ route('package.ajaxShippingExec') }}",
            data:{trackno:trackno, logistic_id:logistic_id},
            dataType:'json', 
            type:'get',
            success:function(result) {
                $('.holder').text('');
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
                }
            }
        });
    });
})
</script>