@extends('common.table')

@section('table_header_search')
    <div class="bjui-searchBar">
        <label>型号：</label>
        <input type="text" class="form-control" size="10" name="size" value="{{ old('size') }}">
        <label>颜色：</label>
        <input type="text" class="form-control" size="10" name="color" value="{{ old('color') }}">
        <label>创建日期：</label>
        <input type="text" class="form-control" size="11" name="created_at" data-toggle="datepicker"
               value="{{ old('created_at') }}">
        <button type="button" class="showMoreSearch" data-toggle="moresearch" data-name="more"><i
                    class="fa fa-angle-double-down"></i></button>
        <button type="submit" class="btn-default" data-icon="search">查询</button>
        <a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reload', true);"
           data-icon="undo">清空查询</a>

        <div class="pull-right">
            <div class="btn-group">
                <button type="button" class="btn-default dropdown-toggle" data-toggle="dropdown" data-icon="copy">
                    复选框-批量操作<span class="caret"></span></button>
                <ul class="dropdown-menu right" role="menu">
                    <li><a href="book1.xlsx" data-toggle="doexport" data-confirm-msg="确定要导出信息吗？">导出<span
                                    style="color: green;">全部</span></a></li>
                    <li><a href="book1.xlsx" data-toggle="doexportchecked" data-confirm-msg="确定要导出选中项吗？"
                           data-idname="expids" data-group="ids">导出<span style="color: red;">选中</span></a></li>
                    <li class="divider"></li>
                    <li><a href="ajaxDone2.html" data-toggle="doajaxchecked" data-confirm-msg="确定要删除选中项吗？"
                           data-idname="delids" data-group="ids">删除选中</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('table_header_more_search')
    <div class="bjui-moreSearch">
        <label>品牌:</label>
        <select name="brand_id" data-toggle="selectpicker">
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
@endsection

@section('table_body')
    <div class="bjui-pageContent tableContent">
        <table data-toggle="tablefixed" data-width="100%" data-nowrap="true">
            <thead>
            <tr>
                @foreach($columns as $column)
                    <th data-order-field="{{ $column['name'] }}">{{ $column['label'] }}</th>
                @endforeach
                <th width="26"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $key => $car)
                <tr data-id="{{ $car->id }}">
                    <td>{{ $car->id }}</td>
                    <td>{{ $car->brand->name }}</td>
                    <td>{{ $car->size }}</td>
                    <td>{{ $car->color }}</td>
                    <td>{{ $car->created_at }}</td>
                    <td><input type="checkbox" name="ids" data-toggle="icheck" value="{{ $car->id }}"></td>
                    <td>
                        <a href="form.html?id=1" class="btn btn-green" data-toggle="navtab" data-id="form"
                           data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="编辑-孙悟空">编辑</a>
                        <a href="ajaxDone2.html" class="btn btn-red" data-toggle="doajax"
                           data-confirm-msg="确定要删除该行信息吗？">删</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection