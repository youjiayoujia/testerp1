@extends('common.table')
@section('tableToolButtons')

    <div class="btn-group btn-info" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量修改属性
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="batchedit" data-name="name">中英文资料</a></li>
            <li><a href="javascript:" class="batchedit" data-name="quality_standard">质检</a></li>
            <li><a href="javascript:" class="batchedit" data-name="package_limit">包装方式</a></li>
            <li><a href="javascript:" class="batchedit" data-name="purchase_url">参考链接</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <a href="javascript:" data-channel="1" data-name="Amazon" class="btn btn-success choseShop">
            选中
        </a>
    </div>

    <div class="btn-group" role="group">
        <div class="form-group" style="margin-bottom:0px">
            <select id="ms" class="js-example-basic-multiple" multiple="multiple" name="select_channel" style="width:200px">
                <option value="1" class='aa'>Amazon</option>
                <option value="2" class='aa'>EBay</option>
                <option value="3" class='aa'>速卖通</option>
                <option value="4" class='aa'>B2C</option>
            </select>
        </div>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            批量审核
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class="shenhe" data-status="pass" data-name="审核通过">通过</a></li>
            <li><a href="javascript:" class="shenhe" data-status="notpass" data-name="审核不通过">不通过</a></li>
            <li><a href="javascript:" class="shenhe" data-status="revocation" data-name="撤销审核">撤销审核</a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 查询审核状态
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ DataList::filtersEncode(['examine_status','=','']) }}">未审核</a></li>
            <li><a href="{{ DataList::filtersEncode(['examine_status','=','pass']) }}">审核通过</a></li>
            <li><a href="{{ DataList::filtersEncode(['examine_status','=','notpass']) }}">审核不通过</a></li>
            <li><a href="{{ DataList::filtersEncode(['examine_status','=','revocation']) }}">撤销审核</a></li>
        </ul>
    </div>

    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 查询编辑状态
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="{{ DataList::filtersEncode(['edit_status','=','picked']) }}">被选中</a></li>
            <li><a href="{{ DataList::filtersEncode(['edit_status','=','data_edited']) }}">资料已编辑</a></li>
            <li><a href="{{ DataList::filtersEncode(['edit_status','=','image_edited']) }}">图片已编辑</a></li>
            <li><a href="{{ DataList::filtersEncode(['edit_status','=','image_unedited']) }}">图片不编辑</a></li>
        </ul>
    </div>
    {{--@can('check','product_admin,product_staff|add')--}}
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>
    {{--@endcan--}}
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th>
    <th class="sort" data-field="model">MODEL</th>
    <th>图片</th>
    <th>产品名称</th>
    <th>分类</th>
    <th>状态</th>
    <th>选中shop</th>
    <th>编辑状态</th>
    <th>审核状态</th>
    <th class="sort" data-field="name">名称</th>
    <th class="sort" data-field="c_name">中文名称</th>
    <th>供应商</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>售价</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $product)
        <tr>
            <td>
                @if($product->status)
                    <input type="checkbox" name="tribute_id" value="{{$product->id}}" isexamine="1">
                @else
                    <input type="checkbox" name="tribute_id" value="{{$product->id}}" isexamine="0">
                @endif
            </td>
            <td>{{ $product->id }}</td>
            <td>{{ $product->model }}</td>
            <td><img src="{{ asset($product->dimage) }}" width="100px"></td>
            <td>{{ $product->c_name }}<br>分类：{{ $product->catalog?$product->catalog->all_name:'' }}<br>开发时间：{{ $product->created_at }}<br></td>
            <td>{{ $product->catalog?$product->catalog->all_name:'' }}</td>
            <td><?php if ($product->edit_status == "") echo "新品上传";if ($product->edit_status == "picked") echo "被选中";if ($product->edit_status == "canceled") echo "取消"; ?></td>
            <td><?php if ($product->amazonProduct) echo "amazon,";if ($product->ebayProduct) echo "ebay,";if ($product->aliexpressProduct) echo "aliexpress,";if ($product->b2cProduct) echo "B2C,"; ?></td>
            <?php switch ($product->edit_status) {
            case '':
            ?>
            <td>新品上传</td>
            <?php
            break;

            case 'canceled':
            ?>
            <td>取消</td>
            <?php
            break;

            case 'picked':
            ?>
            <td>选中</td>
            <?php
            break;

            case 'data_edited':
            ?>
            <td>资料已编辑</td>
            <?php
            break;

            case 'image_edited':
            ?>
            <td>图片已编辑</td>
            <?php
            break;

            case 'image_unedited':
            ?>
            <td>图片不编辑</td>
            <?php
            break;
            } ?>
            <td>{{config('product.examineStatus')[$product->examine_status?$product->examine_status:'pending']}}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->c_name }}</td>
            <td>{{ $product->supplier?$product->supplier->name:'无' }}</td>
            <td>{{ $product->created_at }}</td>
            <td>{{--<button class ="btn btn-success" >计算</button>--}}
                <a href="javascript:" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal_{{$product->id}}">
                    计算
                </a>
                <div class="modal fade" id="myModal_{{$product->id}}" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document" style="width:710px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">计算售价</h4>
                            </div>
                            <div class="modal-body">
                                <form id="compute-form-{{$product->id}}">
                                    <div class="form-group form-inline sets" id="setkey_0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>当前分类：</td>
                                                <td colspan="2">{{ $product->catalog?$product->catalog->all_name:'' }}</td>
                                            </tr>
                                            <tr>
                                                <td>产品名称：</td>
                                                <td colspan="2">{{ $product->c_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>产品重量：
                                                    <div class="input-group">
                                                    <input type="text" class="form-control" id="weight-{{$product->id}}" value="{{$product->weight}}" style="width: 60px;" disabled>
                                                    <span class="input-group-addon">Kg</span>
                                                    </div>

                                                </td>
                                                <td>{{--打包：--}}</td>
                                                <td>
                                                 {{--   <div class="input-group">
                                                        <input type="text" class="form-control" style="width: 60px;">
                                                        <span class="input-group-addon">个</span>
                                                    </div>--}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    物流分类：
                                                    <select class="form-control logistics-catalog-{{$product->id}}" style="width: 150px;" name="logistics-catalog-{{$product->id}}" id="logistics-catalog-{{$product->id}}" onchange="changeSelectVlaue($(this),'catalog',{{$product->id}})">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                      <script>
                                                          $('#logistics-catalog-{{$product->id}}').select2({
                                                              ajax: {
                                                                  url: "{{ route('ajaxReutrnCatalogs') }}",
                                                                  dataType: 'json',
                                                                  delay: 250,
                                                                  data: function (params) {
                                                                      return {
                                                                          name:params.term,
                                                                      };
                                                                  },
                                                                  results: function(data, page) {
                                                                  }
                                                              },
                                                          });
                                                      </script>
                                                </td>
                                                <td>
                                                    物流：
                                                    <select class="form-control" onchange="changeSelectVlaue($(this),'logistics',{{$product->id}})" id="logistics-{{$product->id}}" style="width: 150px;">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    物流分区：
                                                    <select class="form-control" name="division" id="zones-{{$product->id}}" style="width: 150px;">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    渠道名称:
                                                    <select class="form-control" id="channel-{{$product->id}}">
                                                        <option value="none">请选择</option>
                                                    @foreach($Compute_channels as $item)
                                                        <option value="{{$item->name}}">{{$item->name}}</option>
                                                    @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    利润率：
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " style="width:50px" id="profit-{{$product->id}}" value="">
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                </td>
                                                <td>{{--汇率：XXXX--}}</td>
                                            </tr>
                                        </table>
                                        <div class=" text-right">
                                            <input type="button" name="查询" class="form-control btn-primary" placeholder="属性名" value="查 询" onclick="doComputePrice({{$product->id}})">
                                        </div>
                                        <br/>
                                        <table class="table table-bordered table-striped table-hover sortable" style="display: none;" id="result-table-{{$product->id}}">
                                            <thead>
                                            <tr>
                                            <th>序号</th>
                                            <th>渠道名</th>
                                            <th>大PP价格（单位：美元）</th>
                                            <th>小PP价格（单位：美元）</th>
                                            </tr>
                                            </thead>
                                            <tbody id="result-price-{{$product->id}}">

                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <a href="{{ route('product.show', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                {{--@can('check','product_admin,product_staff|edit')--}}
                <a href="{{ route('product.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                {{--@endcan--}}
                <a href="{{ route('productMultiEdit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 小语言
                </a>
                <?php if(($product->edit_status == 'picked' || $product->edit_status == 'data_edited' || $product->edit_status == "image_edited" || $product->edit_status == "image_unedited") && $product->examine_status != 'pass'){ ?>
                <a href="{{ route('EditProduct.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑资料
                </a>
                <?php } ?>
                <a href="{{ route('createImage', ['model'=>$product->model]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑图片
                </a>
                <?php //if($product->edit_status == "image_unedited" || $product->edit_status == "image_edited"){ ?>
                <a href="{{ route('ExamineProduct.edit', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看并审核
                </a>
                <?php //} ?>
{{--@can('check','product_admin,product_staff|delete')--}}
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $product->id }}"
                   data-url="{{ route('product.destroy', ['id' => $product->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
{{--@endcan--}}
            </td>
        </tr>
    @endforeach
@stop
@section('childJs')
    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script type="text/javascript">

        /**
         * 计算价格
         * @param productId
         */
        function doComputePrice(productId){

            var zone_id = $('#zones-'+productId).val();
            var channel_id = $('#channel-'+productId).val();
            var profit_id = $('#profit-'+productId).val();
            var product_weight = $('#weight-'+productId).val();

            if(zone_id == 'none'){
                alert('物流分区不能为空');
                return false;
            }
            if(profit_id == ''){
                alert('利润率不能为空');
                return false;
            }
            if(product_weight == ''){
                alert('产品重量不能为空');
                return false;
            }

            var html = '';
            $.ajax({
                url: "{{  route('product.ajaxReturnPrice') }}",
                dataType: 'json',
                'type': 'get',
                data: {product_id:productId,zone_id:zone_id,channel_id:channel_id,profit_id:profit_id,product_weight:product_weight},
                success:function (returnInfo){

                    if(returnInfo['status'] == 1){
                        $.each(returnInfo['data'],function (i ,item) {
                            html += '<tr>';
                            html += '<td>'+(i+1)+'</td><td>'+item.channel_name+'</td><td>'+item.sale_price_big+'</td><td>'+item.sale_price_small+'</td>';
                            html += '</tr>';

                            $('#result-price-'+productId).html(html);
                            $('#result-table-'+productId).show();
                        });
                    }else{

                    }
                },
                error:function () {

                }
            });

        }

        function getCatalogOption() {

        }
        $('.logistics-catalog').select2({
            ajax: {
                url: "{{ route('ajaxSupplier') }}",
                dataType: 'json',
                delay: 50,
                data: function (params) {
                    return {
                        supplier:params.term,
                    };
                },
                results: function(data, page) {

                }
            },
        });

        function changeSelectVlaue(selected,type,productId){
            var id = selected.val();
            if(id){
                $.ajax({
                    url: "{{route('product.ajaxReturnLogistics')}}",
                    data: {id: id,type:type},
                    dataType: 'json',
                    type: 'get',
                    success: function ($returnInfo) {
                        if($returnInfo != {{config('status.ajax.fail')}}){
                            switch(type){
                                case 'catalog':
                                    $('#logistics-'+productId).html('');
                                    $('#logistics-'+productId).append('<option value="none"> 请选择 </option>');
                                    $.each($returnInfo,function (index,item) {
                                        $('#logistics-'+productId).append('<option value="' + item.id + '">' + item.logistics_type + '</option>');
                                    });
                                case 'logistics':
                                    $('#zones-'+productId).html('');
                                    $('#zones-'+productId).append('<option value="none"> 请选择 </option>');
                                    $.each($returnInfo,function (index,item) {
                                        $('#zones-'+productId).append('<option value="' + item.id + '">' + item.zone + '</option>');
                                    });
                                default:
                                    return false;
                            }
                        }
                    }
                });
            }
        }

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