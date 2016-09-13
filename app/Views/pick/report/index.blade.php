@extends('common.detail')
@section('detailBody')
<p><font color='red' size='5px'>今日拣货数:@if(count($data)){{ $data->sum('today_pick')}}@endif个&nbsp;&nbsp;&nbsp;&nbsp;今日拣货漏检总数:@if(count($data)){{ $data->sum('missing_pick')}}@endif个&nbsp;&nbsp;&nbsp;&nbsp;本月拣货完成总数: @if(count($data)){{ $data->sum('single') + $data->sum('singleMulti') + $data->sum('multi') }}@endif个&nbsp;&nbsp;&nbsp;&nbsp;本月拣货漏检总数: @if(count($data)){{ $data->filter(function($query){ return strtotime($query->day_time) > strtotime(date('Y-m', strtotime('now'))) &&  strtotime($query->day_time) < strtotime(date('Y-m', strtotime('+1 month'))); })->sum('missing_pick')}}@endif个</font></p>
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
<div class='row'>
    <div class='form-group col-lg-1'>
        <button class="btn btn-primary btn-lg assign"
                data-toggle="modal"
                data-target="#all">
            拣货单标记拣货
        </button>
    </div>
    <div class='form-group col-lg-1'>
        <button class="btn btn-primary btn-lg change"
                data-toggle="modal"
                data-target="#all">
            拣货单产量转移
        </button>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">拣货排行榜<a href="{{ route('pickReport.createData') }}">生成数据</a></div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>拣货人员</th>
                <th>拣货组</th>
                <th>本月总拣货完成数(各类型商品数)</th>
                <th>漏检数</th>
                <th>今日拣货数</th>
                <th>今日分配拣货单</th>
            </tr>
            </thead>
            <tbody>
            @if(count($data))
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
            @endif
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="all" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">拣货单信息修改</div>
                    <div class="panel-body">
                        <div class='row'>
                            <div class='form-group col-lg-3'>
                                <input class='form-control picklist_id' name='picklist_id' type="text" placeholder='拣货单号'>
                            </div>
                            <div class='form-group col-lg-3'>
                                <input class='form-control pick_by' name='pick_by' type="text" placeholder='拣货人员'>
                            </div>
                            <div class='form-group col-lg-3'>
                                <button type='button' class='btn btn-success confirm'>确认</button>
                            </div>
                            <input type='hidden' class='buf'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function(){
    $(document).on('click', '.assign', function(){
        picklist = $('.picklist_id').val('');
        pickBy = $('.pick_by').val('');
        $('.buf').val(1);
    });

    $(document).on('click', '.change', function(){
        picklist = $('.picklist_id').val('');
        pickBy = $('.pick_by').val('');
        $('.buf').val(2);
    });

    $(document).on('click', '.confirm', function(){
        id = $('.buf').val();
        picklist = $('.picklist_id').val();
        pickBy = $('.pick_by').val();
        if(picklist && pickBy) {
            $.get(
                "{{route('pickList.changePickBy')}}",
                    {picklist:picklist, pickBy:pickBy, id:id},
                    function(result){
                        if(result == 'false') {
                            alert('拣货单号不存在')
                        }
                    }
                )
        } else {
            alert('拣货单号或拣货人员信息不全');
        }
        $('.assign').click();
    });

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