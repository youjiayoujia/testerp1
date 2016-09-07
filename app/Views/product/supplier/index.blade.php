@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">

        <div class="btn-group">
            &nbsp;
            <a class="btn btn-success" href="{{ route('supplierChangeHistory.index') }}">
                采购员变更历史
            </a>
        </div>
        <div class="btn-group">
            &nbsp;
            <a class="btn btn-success" href="{{ route('supplierLevel.index') }}">
                <i class="glyphicon glyphicon-plus"></i> 供货商评级
            </a>
        </div>
        @parent
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i>
            审核状态
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach(config('product.supplier_examine_status') as $key => $item)
                <li>
                    <a href="{{ DataList::filtersEncode(['examine_status','=',$key]) }}">{{ $item }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th class='sort' data-field='id'>ID</th>
    {{--<th>名称</th>--}}
    <th>公司名称</th>
    <th>详细地址</th>
    <th>供货商类型</th>
    {{--<th>销售网址</th>--}}
    <th>供货商官网</th>
    <th>联系人</th>
    <th class='sort' data-field='telephone'>电话</th>
    <th>旺旺</th>
    <th>QQ</th>
{{--    <th>采购员</th>--}}
    <th>创建人</th>
    <th class='sort' data-field='created_at'>创建时间</th>
    <th>审核状态</th>

    <th class='sort' data-field='level'>供货商等级</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $supplier)
        <tr>
            <td> @if($supplier->examine_status < 2 )
                    <input type="checkbox" name="supplier_id" value="{{$supplier->id}}" isexamine="0">
                @else
                    <input type="checkbox" name="supplier_id" value="{{$supplier->id}}" isexamine="1">
                @endif
            </td>
            <td>{{ $supplier->id }}</td>
            {{--<td>{{ $supplier->name }}</td>--}}
            <td>{{ $supplier->company }}</td>
            <td>{{ $supplier->address }}</td>
            <td>{{ $supplier->type ? ($supplier->type == '1' ? '线上' : '做货') : '线下' }} </td>

{{--            <td>{{ $supplier->url }}</td>--}}
            <td>{{ $supplier->official_url }}</td>
            <td>{{ $supplier->contact_name }}</td>
            <td>{{ $supplier->telephone }}</td>
            <td>{{ $supplier->wangwang }}</td>
            <td>{{ $supplier->qq }}</td>
{{--            <td>{{ $supplier->purchaseName ? $supplier->purchaseName->name : '' }}</td>--}}
            <td>{{ $supplier->createdByName ? $supplier->createdByName->name : '' }}</td>
            <td>{{ $supplier->created_at }}</td>
            <td>@foreach(config('product.product_supplier.examine_status') as $key=>$v)
                    @if($key == $supplier->examine_status)
                        {{$v}}
                    @endif
                @endforeach
            </td>
            <td>{{ $supplier->levelByName ? $supplier->levelByName->name : '' }}</td>
            <td>
                <a href="{{ route('productSupplier.show', ['id'=>$supplier->id]) }}" title="查看" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                <a href="{{ route('productSupplier.edit', ['id'=>$supplier->id]) }}" title="编辑" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                @if($supplier->examine_status != 2)
                    <a href="javascript:" class="btn btn-danger btn-xs delete_item" title="删除"
                       data-id="{{ $supplier->id }}"
                       data-url="{{ route('productSupplier.destroy', ['id' => $supplier->id]) }}">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                 @endif
            </td>
        </tr>
    @endforeach
    @section('doAction')
        <div class="btn-group dropup">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            审核
            <span class="caret"></span>
        </button>

        <ul class="dropdown-menu">
            <li><a href="javascript:" class="examine" data-channel="0" data-name="待审核">待审核</a></li>
            <li><a href="javascript:" class="examine" data-channel="1" data-name="待复审 ">待复审</a></li>
            <li><a href="javascript:" class="examine" data-channel="2" data-name="审核通过">审核通过</a></li>
            <li><a href="javascript:" class="examine" data-channel="3" data-name="审核不通过">审核不通过</a></li>
        </ul>
        </div>
    @stop


@stop
@section('childJs')
    <script type="text/javascript">
        //批量审核
        $('.examine').click(function () {
            if (confirm($(this).data('name') + "确认选中?")) {
                var url = "{{route('beExamine')}}";
                var checkbox = document.getElementsByName("supplier_id");
                var product_ids = "";
                var channel_id = $(this).data('channel');

                for (var i = 0; i < checkbox.length; i++) {
                    if (!checkbox[i].checked)continue;
                    product_ids += checkbox[i].value + ",";
                }
                product_ids = product_ids.substr(0, (product_ids.length) - 1);
                $.ajax({
                    url: url,
                    data: {product_ids: product_ids, channel_id: channel_id},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                })
            }

        });

        //全选
        function quanxuan() {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("supplier_id");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }
    </script>
@stop