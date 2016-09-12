@extends('common.detail')
@section('detailBody')
<p><font color='red' size='5px'>今日拣货数:{{ $data->sum('today_pick')}}个&nbsp;&nbsp;&nbsp;&nbsp;今天拣货漏检总数:{{ $data->sum('missing_pick')}}个&nbsp;&nbsp;&nbsp;&nbsp;本月拣货完成总数: {{ $data->sum('single') + $data->sum('singleMulti') + $data->sum('multi') }}个&nbsp;&nbsp;&nbsp;&nbsp;本月拣货漏检总数: {{ $data->filter(function($query){ return strtotime($query->day_time) > strtotime(date('Y-m', strtotime('now'))) &&  strtotime($query->day_time) < strtotime(date('Y-m', strtotime('+1 month'))); })->sum('missing_pick')}}个</font></p>
<div class='row'>
    <div class='form-group col-lg-3'>
        <select name='warehouse_id' class='warehouse_id form-control'>
            <option value=''>仓库</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id}}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group col-lg-3'>
        <input id="expected_date" class='form-control' name='expected_date' type="text" placeholder='期望上传日期' value="{{ old('expected_date') }}">
    </div>
    <button type='button' class='btn btn-info search'>查找</button>
</div>
<div class="panel panel-default">
    <div class="panel-heading">拣货排行榜<a href="{{ route('pickReport.createData') }}">生成数据</a></div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>拣货人员</th>
                <th>拣货组</th>
                <th>本院总拣货完成数(各类型商品数)</th>
                <th>漏检数</th>
                <th>今日拣货数</th>
                <th>今日分配拣货单</th>
            </tr>
            </thead>
            <tbody>
                @foreach($data->groupBy('user_id') as $userId => $block)
                <tr>
                    <td>{{$block->first()->user ? $block->first()->user->name : ''}}</td>
                    <td>{{$block->first()->warehouse ? $block->first()->warehouse->name : ''}}</td>
                    <td>{{$block->sum('single') + $block->sum('singleMulti') + $block->sum('multi')}}(单单:{{ $block->sum('single')}},
                            单多:{{ $block->sum('singleMulti')}},多多:{{$block->sum('multi')}})</td>
                    <td>{{$block->sum('missing_pick')}}</td>
                    <td>{{$block->sum('today_pick')}}</td>
                    <td><a href="javascript:" data-userid="{{$userId}}" class='pick'>{{$block->sum('today_picklist')}}</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.pick', function(){
        id = $(this).data('userid');
        location.href="{{ route('pickList.index')}}/?checkid=" + id;
    });

    $('#expected_date').cxCalendar();

    $(document).on('click', '.search', function(){
        date = $('#expected_date').val();
        warehouseid = $('.warehouse_id').val();
        location.href="{{ route('pickReport.index')}}/?date=" + date + "&warehouseid=" + warehouseid;
    });
})
</script>