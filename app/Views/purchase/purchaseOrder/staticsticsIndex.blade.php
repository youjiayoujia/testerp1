@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group btn-info" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量修改属性
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="batchedit" data-name="weight">重量</a></li>
            <li><a href="javascript:" class="batchedit" data-name="purchase_price">参考成本</a></li>
            <li><a href="javascript:" class="batchedit" data-name="status">SKU状态</a></li>
            <li><a href="javascript:" class="batchedit" data-name="package_size">体积</a></li>
            <li><a href="javascript:" class="batchedit" data-name="name">中英文资料</a></li>
            <li><a href="javascript:" class="batchedit" data-name="wrap_limit">包装方式</a></li>
            <li><a href="javascript:" class="batchedit" data-name="catalog">分类</a></li>
            <li><a href="javascript:" class="batchdelete" data-name="catalog">批量删除</a></li>
            <li><a href="javascript:" class="" data-toggle="modal" data-target="#myModal">上传表格修改状态</a></li>
        </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th class="sort" data-field="id">采购负责人</th>
    <th>管理的SKU数</th>
    <th class="sort" data-field="sku">必须当天内下单SKU数</th>
    <th class="sort" data-field="c_name">15天缺货订单</th>
    <th>15天内所有订单</th>
    <th>订单缺货率</th>
    <th>缺货总数</th>
    <th>平均缺货天数</th>
    <th>最长缺货天数</th>
    <th>采购单超期</th>
    <th>当月累计下单数量</th>
    <th>当月累计下单总金额（Y）</th>
    <th>累计运费（Z）</th>
    <th>节约成本（A）</th>
    <th>获取时间</th>
@stop

@section('tableBody')
        <tr>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
        </tr>
@stop

@section('childJs')
    
@stop