@extends('common.form')
@section('formAction') {{ route('report.store') }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-4">
        <label>boxNum</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control boxNum" name='boxNum'>
    </div>
    <div class="form-group col-lg-4">
        <label>物流方式</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name='logistics_id' class='form-control logistics'>
        @foreach($logisticses as $logistics)
            <option value="{{ $logistics->id }}">{{ $logistics->code }}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label>追踪号</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control tracking_no" name='tracking_no'>
    </div>
</div>
@stop
@section('formButton')
    <button type="button" class="btn btn-success send">确认发货</button>
    <div class='buf'></div>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.logistics').select2();

    $(document).on('click', '.send', function(){
        boxNum = $('.boxNum').val();
        logistics = $('.logistics').val();
        tracking_no = $('.tracking_no').val();
        if(boxNum && logistics && tracking_no) {
            $.get(
                "{{ route('report.sendExec')}}",
                {boxNum:boxNum, logistics:logistics, tracking_no:tracking_no},
                    function(result){
                        if(result == 'false') {
                            $('.buf').html("<font color='red'>根据boxNum找不到对应箱子</font>");
                            return false;
                        }
                        $('.buf').html("<font color='green'>已发货</font>");
                    }
            );
        }
    })
})
</script>
@stop