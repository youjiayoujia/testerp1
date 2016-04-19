@extends('common.form')
@section('formAction') {{ route('package.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="ordernum" class='control-label'>订单号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') }}" onblur="//return getOrder($(this));">
            <small class="text-danger" style="display:none;" id="errorMsg">
                <i class="glyphicon glyphicon-exclamation-sign"></i>
                订单不存在
            </small>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12" id="itemDiv">
        </div>
    </div>
@stop
@section('pageJs')
    <script type="text/javascript">
        function getOrder(obj) {
            $('#errorMsg').hide();
            $.post(
                    '{{ route("package.ajaxGetOrder") }}',
                    {ordernum: obj.val()},
                    function (response) {
                        if (response != 'error') {
                            $('#itemDiv').html(response);
                            $('#ordernum').attr('disabled', 'disabled');
                        } else {
                            $('#errorMsg').show();
                        }
                    }, 'html'
            );
        }
    </script>
@stop