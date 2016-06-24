@extends('common.form')
@section('formAction') {{ $action }} @stop
@section('formBody')
<div class='row'>
    <div class="form-group col-lg-3">
        <label>excel导入文件</label>
        <input type='file' name='excel'>
    </div>
    <a href='javascript:' class='btn btn-info download'>格式下载</a>
    <font>( CSV字段名称: package_id,logistics_id,tracking_no )</font>
</div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<script type='text/javascript'>
$(document).ready(function(){
    $('.download').click(function(){
        location.href="{{ route('package.downloadType')}}";
    });
});
</script>
