@extends('common.form')
@section('formAction')@stop
@section('formBody')
<div class='row'>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control sku' placeholder='sku'>
    </div>
    <div class='form-group'>
        <button type='button' class='btn btn-info searchSku'>sku查询</button>
    </div>
</div>
<div class='row'>
    <div class='form-group col-lg-2'>
        <input type='text' class='form-control position' placeholder='库位'>
    </div>
    <div class='form-group'>
        <button type='button' class='btn btn-info searchPosition'>库位查询</button>
    </div>
</div>
<div class='buf'>

</div>
@stop
@section('formButton')@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.searchSku').click(function(){
        sku = $('.sku').val();
        if(sku) {
            $.get(
                "{{ route('stock.getSingleSku')}}",
                {sku:sku},
                function(result){
                    if(result == 'false') {
                        alert('sku不存在');
                        $('.sku').val('');
                        $('.buf').html('');
                        exit;
                    }
                    $('.buf').html('');
                    $('.buf').html(result);
                }
            );
        }
    });

    $('.searchPosition').click(function(){
        position = $('.position').val();
        if(position) {
            $.get(
                "{{ route('stock.getSinglePosition')}}",
                {position:position},
                function(result){
                    if(result == 'false') {
                        alert('库位不存在');
                        $('.positon').val('');
                        $('.buf').html('');
                        exit;
                    }
                    $('.buf').html('');
                    $('.buf').html(result);
                }
            );
        }
    });
});
</script>
@stop
