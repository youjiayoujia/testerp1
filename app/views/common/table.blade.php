@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"><strong>@section('tableTitle') @show{{-- 列表名称 --}}</strong></div>
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
                            </span>
                                </div>
                            </div>
                        </form>
                        <div class="text-right col-lg-9">
                            <div class="btn-group">
                                <a class="btn btn-success" href="{{ route(Request::segment(1).'.create') }}">
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
                        <table class="table table-bordered table-striped table-hover">
                            @section('tableColumns')
                                <thead>
                                <tr>
                                    @foreach($columns as $column)
                                        <th>{{ trans(Request::segment(1).'.'.$column) }}</th>
                                    @endforeach
                                    <th>操作</th>
                                </tr>
                                </thead>
                            @show{{-- 列表字段 --}}
                            <tbody>
                            @section('tableBody')
                            @show{{-- 列表数据 --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <span>每页&nbsp;</span>
                        <select>
                            @foreach(Config::get('setting.pageSizes') as $page)
                                <option value="{{ $page }}">{{ $page }}</option>
                            @endforeach
                        </select>
                        <span>&nbsp;条，共 {{ $data->total() }} 条</span>
                    </div>
                    <div class="col-lg-6 text-right">
                        {!! $data->appends(['keywords' => Request::input('keywords')])->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 模拟DELETE删除表单 --}}
    <form method="post" action="" accept-charset="utf-8" id="hiddenDeleteForm">
        <input name="_method" type="hidden" value="delete"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    </form>

    <script type="text/javascript">
        {{-- 提交删除表单  --}}
        $('.delete_item').click(function () {
            if (confirm("确认删除?")) {
                var url = $(this).data('url');
                var id = $(this).data('id');
                var action = url + '/' + id;
                $('#hiddenDeleteForm').attr('action', action);
                $('#hiddenDeleteForm').submit();
            }
        });
    </script>
@stop