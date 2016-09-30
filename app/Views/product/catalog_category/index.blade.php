@extends('common.table')
@section('tableToolButtons')

    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>

@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th>
    <th class="sort" data-field="model">MODEL</th>
    <th>图片</th>
    <th>产品名称</th>
    <th>英文名</th>
    <th>供应商</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')

@stop
@section('childJs')
    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script type="text/javascript">
        //$('#ms').multipleSelect();
        $(".js-example-basic-multiple").select2();
        //批量选中
        $('.choseShop').click(function () {
            var channel_ids = "";
            $("#ms option:selected").each(function () {
                channel_ids += $(this).attr("value") + ",";
            });

            channel_ids = channel_ids.substr(0, (channel_ids.length) - 1);
            if (confirm("确认选中?")) {
                var url = "{{route('beChosed')}}";
                var checkbox = document.getElementsByName("tribute_id");
                var product_ids = "";

                for (var i = 0; i < checkbox.length; i++) {
                    if (!checkbox[i].checked)continue;
                    product_ids += checkbox[i].value + ",";
                }
                product_ids = product_ids.substr(0, (product_ids.length) - 1);
                $.ajax({
                    url: url,
                    data: {product_ids: product_ids, channel_ids: channel_ids},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        window.location.reload();
                    }
                })
            }
        });
        
        

        $('.batchedit').click(function () {
            var checkbox = document.getElementsByName("tribute_id");
            var product_ids = "";
            var param = $(this).data("name");
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                product_ids += checkbox[i].value + ",";
            }
            product_ids = product_ids.substr(0, (product_ids.length) - 1);
            var url = "{{ route('productBatchEdit') }}";
            window.location.href = url + "?product_ids=" + product_ids + "&param=" + param;
        });

        //批量审核
        $('.shenhe').click(function () {
            if (confirm("确认" + $(this).data('name') + "?")) {
                var url = "{{route('productExamineAll')}}";
                var checkbox = document.getElementsByName("tribute_id");
                var product_ids = "";
                var examine_status = $(this).data('status');

                for (var i = 0; i < checkbox.length; i++) {
                    if (!checkbox[i].checked)continue;
                    product_ids += checkbox[i].value + ",";
                }
                product_ids = product_ids.substr(0, (product_ids.length) - 1);
                $.ajax({
                    url: url,
                    data: {product_ids: product_ids, examine_status: examine_status},
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
            var coll = document.getElementsByName("tribute_id");
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