@extends('common.detail')
@section('detailBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID</label>
            <input type='text' class='form-control' value={{ $model->picknum }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>类型</label>
            <input type='text' class='form-control' value={{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>创建人</label>
            <input type='text' class='form-control' value={{ $model->pickByName ? $model->pickByName->name : '' }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>创建时间</label>
            <input type='text' class='form-control' value={{ $model->pick_at }}>
        </div>
    </div>
    <table class='table table-striped'>
        <tbody>
            <tr>
                <td>包裹总数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'all']) }}">{{ $model->package->count() }}</a></td>
                <td>异常包裹数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'ERROR']) }}">{{ $model->package()->where('status', 'ERROR')->count() }}</a></td>
            </tr>
            <tr>
                <td>未包装总数:</td><td>{{ $model->package()->whereIn('status', ['PICKING', 'NEW'])->count() }} <button type='button' href="javascript:" class='btn btn-info print' data-id="{{ $model->id }}">打印</button></td>
                <td>已打印(拣货中)总数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'PICKING']) }}">{{ $model->package()->where('status', 'PICKING')->count() }}</a></td>
            </tr>
            <tr>
                <td>已包装总数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'PACKED']) }}">{{ $model->package()->where('status', 'PACKED')->count() }}</a></td>
                <td>已发货总数:</td><td><a href="{{ route('pickList.printPackageDetails', ['id' => $model->id, 'status' => 'SHIPPED']) }}">{{ $model->package()->where('status', 'SHIPPED')->count() }}</a></td>
            </tr>
        </tbody>
    </table>
    <iframe src='' id='iframe_print' style='display:none'></iframe>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.print', function () {
        id = $(this).data('id');
        src = "{{ route('pickList.print', ['id'=>'']) }}/" + id;
        $('#iframe_print').attr('src', src);
        $('#iframe_print').load(function () {
            $('#iframe_print')[0].contentWindow.focus();
            $('#iframe_print')[0].contentWindow.print();
        });
    });
})
</script>