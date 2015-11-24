@extends('layouts.default')

@section('content')
    <div id="toolbar">
        @section('gridToolbar')
            <a href="{{ route(Request::segment(1).'.create') }}">
                <button class="btn btn-success">
                    <i class="glyphicon glyphicon-plus"></i> 新增
                </button>
            </a>
        @show{{-- 工具栏 --}}
    </div>
    <table id="table"
           @section('gridConfig')
           data-classes="table table-striped table-hover"
           data-toolbar="#toolbar"
           data-show-columns="true"
           data-show-export="true"
           data-search="true"
           data-show-refresh="true"
           data-minimum-count-columns="2"
           data-pagination="true"
           data-method="post"
           data-query-params-type=''
           data-page-list="[10, 25, 50, 100, ALL]"
           data-side-pagination="server"
           data-url="{{ route(Request::segment(1).'.grid') }}"
            @show{{-- Bootstarp Table 配置 --}}
    >
    </table>
    <script type="text/javascript">
        $('#table').bootstrapTable({
            columns:@section('gridColumns')@show{{-- gird 字段 --}},
        });
    </script>
@stop
