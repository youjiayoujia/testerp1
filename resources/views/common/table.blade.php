@section('table_header')
    <div class="bjui-pageHeader">
        <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @section('table_header_search')
            @show{{-- 查询 --}}
            @section('table_header_more_search')
            @show{{-- 更多查询 --}}
        </form>
    </div>
@show{{-- 列表头部 --}}
@stop

@section('table_body')
@show{{-- 列表内容 --}}
@stop

@section('table_footer')
    <div class="bjui-pageFooter">
        <div class="pages">
            <span>每页&nbsp;</span>

            <div class="selectPagesize">
                <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                    @foreach(Config::get('setting.pageSizes') as $page)
                        <option value="{{ $page }}">{{ $page }}</option>
                    @endforeach
                </select>
            </div>
            <span>&nbsp;条，共 {{ $datas->total() }} 条</span>
        </div>
        <div class="pagination-box" data-toggle="pagination" data-total="{{ $datas->total() }}"
             data-page-size="{{ $datas->perPage() }}" data-page-current="1">
        </div>
    </div>
@show{{-- 列表底部 --}}
@stop