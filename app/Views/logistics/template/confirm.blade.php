@extends('common.detail')
@section('detailBody')
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="package_id" class="control-label">包裹ID</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="package_id" placeholder="包裹ID" name='package_id'>
        </div>
        <div class="form-group col-lg-2">
            <label for="logistics_id" class='control-label'>物流方式</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select class="form-control logistics_id" id="logistics_id" name="logistics_id"></select>
        </div>
    </div>
@stop
@section('pageJs')
    <script type='text/javascript'>

    </script>
@stop