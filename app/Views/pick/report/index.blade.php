@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading">拣货排行榜<a href="{{ route('pickReport.createData') }}">生成数据</a></div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                
            </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
    </div>
</div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.all', function () {
        id = $(this).data('channelid');
        location.href="{{route('package.index')}}/?outer=all&id="+id;
    });

    $(document).on('click', '.single', function () {
        id = $(this).data('channelid');
        outer = $(this).data('warehouseid');
        flag = $(this).data('flag');
        location.href="{{route('package.index')}}/?outer="+outer+"&id="+id+"&flag="+flag;
    });
})
</script>