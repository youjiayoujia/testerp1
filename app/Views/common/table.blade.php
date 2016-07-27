@extends('layouts.default')
@section('content')
    <div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
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
                                    <input type="text" class="form-control" name="keywords" value="{{ old('keywords') }}" placeholder="{{ isset($data) ? (count($data) ? $data->first()->showSearch() : '') : '' }}"/>
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            <i class="glyphicon glyphicon-search"></i>
                                        </button>
                                        <a class="btn btn-default" href="{{ request()->url() }}">
                                            <i class="glyphicon glyphicon-remove"></i>
                                        </a>
                                        @if(isset($mixedSearchFields))
                                            <a class="btn btn-primary" role="button" data-toggle="collapse"
                                               href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                更多查询
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="text-right col-lg-9">
                            @section('tableToolButtons')
                                <div class="btn-group">
                                    <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
                                        <i class="glyphicon glyphicon-plus"></i> 新增
                                    </a>
                                </div>
                            @show{{-- 工具按钮 --}}
                        </div>
                    </div>
                    @if(isset($mixedSearchFields))
                        <div class="col-lg-12">
                            <div class="collapse" id="collapseExample">
                                <form action="" method="get">
                                    <div class="well row">
                                        @foreach($mixedSearchFields as $type => $value)
                                            @if($type == 'doubleRelatedSearchFields')
                                                @foreach($value as $relation_ship1 => $value1)
                                                    @foreach($value1 as $relation_ship2 => $value2)
                                                        @foreach($value2 as $key => $name)
                                                            <div class="col-lg-2">
                                                                <input type="text" class="form-control" name="mixedSearchFields[{{$type}}][{{ $relation_ship1 }}][{{ $relation_ship2 }}][{{ $name }}]" placeholder="{{ config('setting.transfer_search')[$relation_ship1.'.'.$relation_ship2.'.'.$name] }}"/>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            @endif
                                            @if($type == 'relatedSearchFields')
                                                @if(count($value))
                                                    @foreach($value as $relation_ship => $name_arr)
                                                        @foreach($name_arr as $name)
                                                            <div class="col-lg-2">
                                                                <input type="text" class="form-control" name="mixedSearchFields[{{$type}}][{{ $relation_ship }}][{{ $name }}]" placeholder="{{ config('setting.transfer_search')[$relation_ship.'.'.$name] }}"/>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            @endif
                                            @if($type == 'filterFields')
                                                @foreach($value as $name)
                                                    <div class="col-lg-2">
                                                        <input type="text" class="form-control" name="mixedSearchFields[{{$type}}][{{ $name }}]" placeholder="{{ config('setting.transfer_search')[$name] }}"/>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if($type == 'filterSelects')
                                                @foreach($value as $name => $content)
                                                    <div class="col-lg-2">
                                                        <select name="mixedSearchFields[{{$type}}][{{ $name }}]" class='form-control select_select0 col-lg-2'>
                                                            <option value=''>{{config('setting.transfer_search')[$name]}}</option>
                                                            @foreach($content as $k => $v)
                                                                <option value="{{ $k }}">{{$v}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if($type == 'selectRelatedSearchs')
                                                @foreach($value as $relation_ship => $contents)
                                                    @foreach($contents as $name => $single)
                                                        <div class='col-lg-2'>
                                                            <select name="mixedSearchFields[{{$type}}][{{ $relation_ship }}][{{ $name }}]" class='form-control select_select0 col-lg-2'>
                                                                <option value=''>{{config('setting.transfer_search')[$relation_ship.'.'.$name]}}</option>
                                                                @foreach($single as $key => $value1)
                                                                    <option value="{{ $key }}">{{$value1}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                            @if($type == 'sectionSelect')
                                                @foreach($value as $kind => $contents)
                                                    @foreach($contents as $content)
                                                        @if($kind == 'time')
                                                            <div class='col-lg-2'>
                                                                <input type='text' class='form-control datetime_select' name="mixedSearchFields[{{$type}}][{{$content}}][begin]" placeholder="起始{{config('setting.transfer_search')[$kind.'.'.$content]}}">
                                                            </div>
                                                            <div class='col-lg-2'>
                                                                <input type='text' class='form-control datetime_select' name="mixedSearchFields[{{$type}}][{{$content}}][end]" placeholder="结束{{config('setting.transfer_search')[$kind.'.'.$content]}}">
                                                            </div>
                                                        @endif
                                                        @if($kind == 'price')
                                                            <div class='col-lg-2'>
                                                                <input type='text' class='form-control' name="mixedSearchFields[{{$type}}][{{$content}}][begin]" placeholder="起始{{config('setting.transfer_search')[$kind.'.'.$content]}}">
                                                            </div>
                                                            <div class='col-lg-2'>
                                                                <input type='text' class='form-control' name="mixedSearchFields[{{$type}}][{{$content}}][end]" placeholder="结束{{config('setting.transfer_search')[$kind.'.'.$content]}}">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <div class="col-lg-1">
                                            <button class="btn btn-success" type="submit">提交</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
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
                        <select id="pageSize">
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
                        'filters' => request()->input('filters'),
                        'filterClear' => request()->input('filterClear'),
                        'mixedSearchFields' => request()->input('mixedSearchFields'),
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
            location.href = new URI().setQuery('pageSize', size);
        });
        {{-- 排序 --}}
        $('.sort').click(function () {
            var field = $(this).data('field');
            var uri = new URI();
            uri.hasQuery('sorts', function (value) {
                var hasField = 0;
                var sortsNew;
                if (value) {
                    var srotsQuery = value.split(',');
                    $.each(srotsQuery, function (sortsKey, sortsValue) {
                        var sort = sortsValue.split('.');
                        if (sort[0] == field) {
                            hasField = 1;
                            sortsNew = sort[1] == 'asc' ? value.replace(field + '.asc', field + '.desc') : value.replace(field + '.desc', field + '.asc');
                        }
                    });
                    if (hasField == 0) {
                        sortsNew = value + "," + field + '.asc';
                    }
                } else {
                    sortsNew = field + '.asc';
                }
                location.href = uri.setQuery('sorts', sortsNew);
            });
        });
        $('.sort').each(function (k, obj) {
            var field = $(obj).data('field');
            new URI().hasQuery('sorts', function (value) {
                if (value) {
                    var srotsQuery = value.split(',');
                    $.each(srotsQuery, function (sortsKey, sortsValue) {
                        var sort = sortsValue.split('.');
                        if (sort[0] == field) {
                            sort[1] == 'asc' ? $(obj).append('<span class="sign arrow up"></span>') : $(obj).append('<span class="sign arrow"></span>');
                        }
                    });
                }
            });
        });

        $('.datetime_select').datetimepicker({theme: 'dark'});
        $('.select_select0').select2();
    </script>
@section('childJs')@show
@stop
