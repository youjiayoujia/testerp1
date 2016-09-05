@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 查询当前状态
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">

        </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>图片</th>
    <th>采购员</th>
    <th>编辑</th>
    <th>美工</th>
    <th>开发</th>
    <th>当前进度</th>
    <th>备注</th>
    <th class="sort" data-field="created_at">录入时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')



        <!-- 模态框（Modal） -->

        <!-- 模态框结束（Modal） -->



@section('doAction')
@stop
<br>
@stop

@section('childJs')
@stop
