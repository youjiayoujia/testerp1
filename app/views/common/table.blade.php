@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>@section('tableTitle') {{ $metas['title'] }} @show{{-- 列表标题 --}}</strong>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                @section('tableToolbar')
                    <div class="row toolbar">
                        <form action="" method="get">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keywords" value="{{ old('keywords') }}" placeholder="查找..."/>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                                <a class="btn btn-default" href="{{ request()->url() }}">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </a>
                            </span>
                                </div>
                            </div>
                        </form>
                        <div class="text-right col-lg-9">
                            <div class="btn-group">
                                <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
                                    <i class="glyphicon glyphicon-plus"></i> 新增
                                </a>
                                <a class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    导出 <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">CSV</a></li>
                                    <li><a href="#">PDF</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">亚马逊模版</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @show{{-- 列表工具栏 --}}
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-striped table-hover sortable">
                            <thead>
                            <tr>
                                @section('tableHeader')@show{{-- 列表字段 --}}
                            </tr>
                            </thead>
                            <tbody>
                            @section('tableBody')@show{{-- 列表数据 --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <span>每页&nbsp;</span>
                        <select id="pageSize" data-url="{{ request()->url() }}">
                            @foreach(config('setting.pageSizes') as $page)
                                <option value="{{ $page }}" {{ $page == request()->input('pageSize') ? 'selected' : '' }}>
                                    {{ $page }}
                                </option>
                            @endforeach
                        </select>
                        <span>&nbsp;条，共 {{ $data->total() }} 条</span>
                    </div>
                    <div class="col-lg-6 text-right">
                        {!!
                        $data
                        ->appends([
                        'keywords' => request()->input('keywords'),
                        'pageSize' => request()->input('pageSize'),
                        'sorts' => request()->input('sorts'),
                        ])
                        ->render()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 模拟DELETE删除表单 --}}
    <form method="POST" action="" id="hiddenDeleteForm">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="DELETE"/>
    </form>
@stop
@section('pageJs')
    <script type="text/javascript">
        {{-- 提交删除表单  --}}
        $('.delete_item').click(function () {
            if (confirm("确认删除?")) {
                var url = $(this).data('url');
                $('#hiddenDeleteForm').attr('action', url);
                $('#hiddenDeleteForm').submit();
            }
        });
        {{-- 更改显示条数  --}}
        $('#pageSize').change(function () {
            var size = $(this).val();
            var url = $(this).data('url');
            var action = url + '?pageSize=' + size;
            location.href = action;
        });
        {{-- 排序 --}}
        $('.sort').click(function () {
            location.href = $(this).data('url');
        });
    </script>
@stop