@extends('common.form')
@section('formAction') {{ route('package.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-12">
            <label for="ordernum" class='control-label'>订单号</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="ordernum" placeholder="订单号" name='ordernum' value="{{ old('ordernum') }}" onblur="return getOrder($(this));">
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
            $.post(
                    '{{ route("package.ajaxGetOrder") }}',
                    {ordernum: obj.val()},
                    function (response) {
                        if (response != 'error') {
                            $('#itemDiv').html(response);
                            $('#ordernum').attr('disabled', 'disabled');
                        } else {
                            alert("Can't find this order.");
                        }
                    }, 'html'
            );
        }
    </script>
@stop