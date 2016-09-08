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
    <th class="sort" data-field="id">ID</th>
    <th>图片</th>
    <th class="sort" data-field="sku">产品名称</th>
    <th class="sort" data-field="c_name">sku</th>
    <th>重量</th>
    <th>仓位</th>
    <th>申报资料</th>
    <th>注意事项</th>
    <th>小计</th>
    <th>状态</th>
    <th>采购负责人</th>
    <th>开发负责人</th>
    <th>供应商</th>
    <th>售价</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $item)
        <tr>
            <td><input type="checkbox" name="tribute_id" value="{{$item->id}}"></td>
            <td>{{ $item->id }}</td>
            <td><img src="{{ asset($item->product->dimage) }}" width="100px"></td>
            <td>{{ $item->c_name }}<br>物品分类：{{ $item->product->catalog?$item->product->catalog->all_name:'' }}<br>
                                    开发时间：{{ $item->created_at }}<br>
                                    【包装方式：<br>
                                    @foreach($item->product->wrapLimit as $wrap)
                                        {{$wrap->name}}<br>
                                    @endforeach
                                    】
                                </td>
            <td>{{ $item->sku }}</td>
            <td>{{ $item->weight }}kg</td>
            <td>{{ $item->warehouse?$item->warehouse->name:'' }}<br>{{ $item->warehousePosition?$item->warehousePosition->name:'' }}</td>
            <td>{{ $item->product?$item->product->declared_en:'' }}<br>{{ $item->product?$item->product->declared_cn:'' }}<br>
                    $<?php
                        if($item->product){
                                if($item->product->declared_value>0){
                                    echo $item->product->declared_value;
                                }elseif(($item->purchase_price/6)<1){echo 1;}elseif(($item->purchase_price/6)>25){echo 25;}else{echo round($item->purchase_price/6);}
                        }
                    ?>
            </td>
            <td>{{$item->product?$item->product->notify:''}}</td>
            <td>
                <div>虚：{{$item->available_quantity}}</div>
                <div>实：{{$item->all_quantity}}</div>
                <div>途：{{$item->normal_transit_quantity}}</div>
                <div>特：{{$item->special_transit_quantity}}</div>
                <div>7天销量：{{$item->getsales('-7 day')}}</div>
                <div>14天销量：{{$item->getsales('-14 day')}}</div>
                <div>28天销量：{{$item->getsales('-28 day')}}</div>
                <div>建议采购值：{{$item->getNeedPurchase()}}</div>
                <div>库存周数：{{$item->getsales('-7 day')==0?0:($item->available_quantity+$item->normal_transit_quantity)/$item->getsales('-7 day')}}</div>
            </td>
            <td>{{ config('item.status')[$item->status]}}</td>
            <td>{{ $item->purchaseAdminer?$item->purchaseAdminer->name:''}}</td>
            <td>{{ $item->product->spu->Developer?$item->product->spu->Developer->name:''}}</td>
            <td>{{ $item->supplier ? $item->supplier->name :''}}</td>
            <td>{{--<button class ="btn btn-success" >计算</button>--}}
                <a href="javascript:" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal_{{$item->id}}">
                    计算
                </a>



                <div class="modal fade" id="myModal_{{$item->id}}" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document" style="width:710px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">计算售价</h4>
                            </div>
                            <div class="modal-body">
                                <form id="compute-form-{{$item->id}}">
                                    <div class="form-group form-inline sets" id="setkey_0">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td colspan="3">当前分类：{{ $item->catalog?$item->catalog->all_name:'' }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">产品名称：{{ $item->c_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>产品重量：
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="weight-{{$item->id}}" value="{{$item->weight}}" style="width: 110px;"  disabled>
                                                        <span class="input-group-addon">Kg</span>
                                                    </div>

                                                </td>
                                                <td>
                                                    渠道名称：
                                                    <select class="form-control" id="channel-{{$item->id}}">
                                                        <option value="none">请选择</option>
                                                        @foreach($Compute_channels as $channel)
                                                            <option value="{{$channel->name}}">{{$channel->name}}</option>
                                                        @endforeach
                                                    </select></td>
                                                <td>

                                                    利润率：
                                                    <div class="input-group">
                                                        <input type="text" class="form-control " style="width:50px" id="profit-{{$item->id}}" value="20">
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                    {{--   <div class="input-group">
                                                           <input type="text" class="form-control" style="width: 60px;">
                                                           <span class="input-group-addon">个</span>
                                                       </div>--}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    物流分类：
                                                    <select class="form-control logistics-catalog-{{$item->id}}" style="width: 150px;" name="logistics-catalog-{{$item->id}}" id="logistics-catalog-{{$item->id}}" onchange="changeSelectVlaue($(this),'catalog',{{$item->id}})">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                    <script>
                                                        $('#logistics-catalog-{{$item->id}}').select2({
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
                                                    <select class="form-control" onchange="changeSelectVlaue($(this),'logistics',{{$item->id}})" id="logistics-{{$item->id}}" style="width: 150px;">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    物流分区：
                                                    <select class="form-control" name="division" id="zones-{{$item->id}}">
                                                        <option value="none">请选择</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tr>
                                        </table>
                                        <div class=" text-right">
                                            <input type="button" value="取 消" class="form-control btn-default" data-dismiss="modal" aria-label="Close">
                                            <input type="button" name="查询" class="form-control btn-primary" placeholder="属性名" value="查 询" onclick="doComputePrice({{$item->id}})">
                                        </div>
                                        <br/>
                                        <table class="table table-bordered table-striped table-hover sortable" style="display: none;" id="result-table-{{$item->id}}">
                                            <thead>
                                            <tr>
                                                <th>序号</th>
                                                <th>渠道名</th>
                                                <th>大PP价格（单位：美元）</th>
                                                <th>小PP价格（单位：美元）</th>
                                            </tr>
                                            </thead>
                                            <tbody id="result-price-{{$item->id}}">

                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td>{{ $item->updated_at }}</td>
            <td>{{ $item->created_at }}</td>
            <td>
                <a href="{{ route('item.show', ['id'=>$item->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
                </a>
                <a href="{{ route('item.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="{{ route('item.print', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs" data-id="{{ $item->id }}">
                    <span class="glyphicon glyphicon-pencil"></span> 打印
                </a>
                <a data-toggle="modal" data-target="#switch_purchase_{{$item->id}}" title="转移采购负责人" class="btn btn-info btn-xs" id="find_shipment">
                    <span class="glyphicon glyphicon-zoom-in">转移采购员</span>
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route('item.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
        <!-- 模态框（Modal）转采购负责人 -->
        <form action="/item/changePurchaseAdmin/{{$item->id}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="switch_purchase_{{$item->id}}"  role="dialog" 
               aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" 
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           转移采购负责人
                        </h4>
                     </div>
                     
                     <div>当前采购负责人:{{$item->purchaseAdminer?$item->purchaseAdminer->name:'无负责人'}}</div>
                     <div>转移至：</div>
                     <div><select class='form-control purchase_adminer' name="purchase_adminer" id="{{$item->id}}"></select></div>
                     或者：
                     <input type="text" value='' name='manual_name' id='manual_name'>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                           data-dismiss="modal">关闭
                        </button>
                        <button type="submit" class="btn btn-primary" name='edit_status' value='image_unedited'>
                           提交
                        </button>
                     </div>
                  </div>
            </div>
            </div>
        </form>
        <!-- 模态框结束（Modal） -->
    @endforeach

        <!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close"
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               上传表格修改sku状态
            </h4>
         </div>
             <form action="{{ route('item.uploadSku') }}" method="post" enctype="multipart/form-data">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                 <input type="file" name="upload" >
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                       data-dismiss="modal">关闭
                    </button>
                    <button type="submit" class="btn btn-primary" >
                       提交
                    </button>
                 </div>
             </form>
        </div>
    </div>
</div>


@stop

@section('childJs')
    <link href="{{ asset('css/multiple-select.css') }}" rel="stylesheet">
    <script src="{{ asset('js/multiple-select.js') }}"></script>
    <script type="text/javascript">
        $('.batchedit').click(function () {
            var checkbox = document.getElementsByName("tribute_id");
            var item_ids = "";
            var param = $(this).data("name");
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                item_ids += checkbox[i].value + ",";
            }
            item_ids = item_ids.substr(0, (item_ids.length) - 1);

            var url = "{{ route('batchEdit') }}";
            window.location.href = url + "?item_ids=" + item_ids + "&param=" + param;
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

        $('.batchdelete').click(function () {

            var url = "{{route('item.batchDelete')}}";

            var checkbox = document.getElementsByName("tribute_id");
            var item_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                item_ids += checkbox[i].value + ",";
            }
            item_ids = item_ids.substr(0, (item_ids.length) - 1);

            $.ajax({
                url: url,
                data: {item_ids:item_ids},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    window.location.reload();
                }
            })
        });

        /*ajax调取采购负责人*/
        $('.purchase_adminer').select2({
            //alert(1);return;
            ajax: {
                url: "{{ route('item.ajaxSupplierUser') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    user:params.term,
                    item_id: $(this).attr('id'),
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
                                        $('#logistics-'+productId).append('<option value="' + item.id + '">' + item.code + '</option>');
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
                        alert('出错了，请检查下物流分区，汇率是否有误 ');
                    }
                },
                error:function () {
                    alert('计算失败，品类渠道的税率是否编辑？');

                }
            });

        }

    </script>
@stop