@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" >
        <a href="/purchaseOrder/purchaseOrdersOut" class="btn btn-info" id="orderExcelOut"> 采购单导出
        </a>
    </div>
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route(request()->segment(1).'.create') }}">
            <i class="glyphicon glyphicon-plus"></i> 新增
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	<th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th>ID</th> 
    <th>采购单状态</th>
    <th>付款状态</th>
    <th>采购人</th>
   	<th>供应商</th>
    <th>采购物品</th>
    <th>采购去向</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @if(count($data)>0)
    @foreach($data as $purchaseOrder)

        <tr>
            <td><input type="checkbox" name="tribute_id" value="{{$purchaseOrder->id}}"></td>
            <td>单据号：NO.{{$purchaseOrder->id }}</br>
            	付款方式：{{$purchaseOrder->supplier?$purchaseOrder->supplier->pay_type:''}}</br>
                外部单号：
                {{$purchaseOrder->post_coding }}
            </td>
            <td> 
                <div>采购单状态：{{config('purchase.purchaseOrder.status')[$purchaseOrder->status]}}</div><br>
                <div>审核状态：{{config('purchase.purchaseOrder.examineStatus')[$purchaseOrder->examineStatus]}}</div><br>
                <div>核销状态：{{config('purchase.purchaseOrder.write_off')[$purchaseOrder->write_off]}}</div>
            </td>
            <td>{{config('purchase.purchaseOrder.close_status')[$purchaseOrder->close_status]}}</td>   
    		<td>{{ $purchaseOrder->purchaseUser?$purchaseOrder->purchaseUser->name:'' }}
            </td>
            <td>
                {{ $purchaseOrder->supplier?$purchaseOrder->supplier->name:''}}
            </td>
            <td>
            @if($purchaseOrder->status <4)
                <table class="table table-bordered table-striped table-hover sortable">
                <thead>
                <tr>
                <th>sku</th>
                <th>状态</th>
                <th>名称</th>
                <th>采购数量</th>
                <th>已到货数量</th>
                <th>入库数量</th>
                <th>不合格</th>
                
                <th>实际到货日期</th>
                <th>状态</th>
                <th>单价</th>
                <th>系统采购价格</th>
                <th>小计</th>
                <th>入库金额</th>
                <th>审单备注</th>
                </tr>
                </thead>
                <tbody>
                @foreach($purchaseOrder->purchase_items as $purchase_item)
                <tr>
                    <td>{{$purchase_item->sku}}</td>
                    <td>{{config('item.status')[$purchase_item->productItem?$purchase_item->productItem->status:'notFound']}}</td>
                    <td>{{$purchase_item->item?$purchase_item->item->c_name:''}}</td>
                    <td>{{$purchase_item->purchase_num}}</td>
                    <td>{{$purchase_item->arrival_num}}</td>
                    <td>{{$purchase_item->storage_qty}}</td>
                    <td>{{$purchase_item->active_num}}</td>
                    
                    <td>{{$purchase_item->arrival_time}}</td>
                    <td>
                        @foreach(config('purchase.purchaseItem.status') as $key=>$status)
                            {{$purchase_item->status == $key ? $status : ''}}
                        @endforeach
                    </td>
                    <td>{{$purchase_item->purchase_cost}}</td>
                    <td>{{$purchase_item->item?$purchase_item->item->purchase_price:''}}</td>
                    <td>{{$purchase_item->purchase_cost * $purchase_item->purchase_num}}</td>
                    <td>{{$purchase_item->purchase_cost * $purchase_item->storage_qty}}</td>
                    <td>{{$purchase_item->remark}}</td>
                </tr>
                @endforeach
                <tr>
                    <th>合计</th>
                    <th>&nbsp;</th>
                    <th>{{ $purchaseOrder->sum_purchase_num}}</th>
                    <th>{{ $purchaseOrder->sum_arrival_num}}</th>
                    <th>{{ $purchaseOrder->sum_storage_qty}}</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>{{ $purchaseOrder->sum_purchase_account}}+YF{{$purchaseOrder->purchase_post_num}}={{$purchaseOrder->sum_purchase_account+$purchaseOrder->purchase_post_num}}</th>
                    <th>{{ $purchaseOrder->sum_purchase_storage_account}}</th>
                    <th>&nbsp;</th>
                </tr>
                </tbody>
                </table>
                @endif
            </td>
            <td>{{ $purchaseOrder->warehouse ? $purchaseOrder->warehouse->name : '暂无仓库'}}</td>
                 
            <td>{{ $purchaseOrder->created_at }}</td>
            <td>
                @if($purchaseOrder->examineStatus==2||$purchaseOrder->examineStatus==0)
                	<a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" title="审核" class="btn btn-info btn-xs">
                         <span class="glyphicon glyphicon-ok-sign"></span>
                    </a>
                @endif
                <a href="{{ route('purchaseOrder.show', ['id'=>$purchaseOrder->id]) }}"  title="详情"  class="btn btn-info btn-xs">
                     <span class="glyphicon glyphicon-eye-open"></span>  
                </a>
                @if($purchaseOrder->status != 4 || ($purchaseOrder->close_status==1&&$purchaseOrder->status==2))
                    <a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" title="修改" class="btn btn-warning btn-xs">
                       <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                @endif
                @if($purchaseOrder->status != 4&& $purchaseOrder->write_off==0)
                    <a  href="javascript:"  title="待核销" class="btn btn-danger btn-xs daihexiao" data-url="/purchaseOrder/write_off/{{$purchaseOrder->id}}?off={{$purchaseOrder->write_off}}">
                         <span class="glyphicon glyphicon-yen"></span>
                    </a>
                @endif

                @if($purchaseOrder->status != 4&& $purchaseOrder->write_off==1)
                    <a  href="javascript:" title="核销" class="btn btn-success btn-xs hexiao" data-url="/purchaseOrder/write_off/{{$purchaseOrder->id}}?off={{$purchaseOrder->write_off}}">
                         <span class="glyphicon glyphicon-yen"></span>
                    </a>
                @endif
                @if($purchaseOrder->status == 1|| $purchaseOrder->status == 2||$purchaseOrder->status == 3)
                <a data-toggle="modal" data-target="#myModal_{{$purchaseOrder->id}}" title="添加物流单号" class="btn btn-info btn-xs setPurchaseOrder" data-id="{{$purchaseOrder->id}}" >
                    <span class="glyphicon glyphicon-plus"></span>
                </a> 
                
                <a data-toggle="modal" data-target="#myModala" title="查询物流单号" class="btn btn-primary btn-xs" id="find_shipment">
                    <span class="glyphicon glyphicon-zoom-in"></span>
                </a>
                @endif 
                @if($purchaseOrder->examineStatus == 1||$purchaseOrder->examineStatus == 2)
                    <a href="/purchaseOrder/cancelOrder/{{$purchaseOrder->id}}" title="退回" class="btn btn-danger btn-xs tuihui">
                        <span class="glyphicon glyphicon-remove-sign"></span>
                    </a>
                @endif
                @if($purchaseOrder->status == 1&&$purchaseOrder->close_status==0)
                <a href="javascript:" title="付款" data-url="/purchaseOrder/payOrder/{{$purchaseOrder->id}}" class="btn btn-info btn-xs fukuan" data-url="/purchaseOrder/payOrder/{{$purchaseOrder->id}}">
                    <span class="glyphicon glyphicon glyphicon-usd"></span>
                </a>
                @endif 
                
				<a href="/purchaseOrder/printOrder/{{$purchaseOrder->id}}" title="打印" class="btn btn-primary btn-xs">
                    <span class="glyphicon glyphicon-print"></span>
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $purchaseOrder->id }}"
                   data-url="{{ route('purchaseOrder.destroy', ['id' => $purchaseOrder->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span>
                </a> 
            </td>
        </tr>
        <!-- 模态框（Modal） -->
<div class="modal fade" id="myModal_{{$purchaseOrder->id}}" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               添加物流单号
            </h4>
         </div>
         <form action="/purchaseOrder/addPost/{{$purchaseOrder->id}}" method="post">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
         <div class="panel panel-default">
        <div class="panel-heading">产品信息</div>
        
        <div class="panel-body" id="itemDiv">
            <div class='row'>
                <div class="form-group col-sm-2">
                    <label  class='control-label'>物流号</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div> 
                <div class="form-group col-sm-2">
                    <label  class='control-label'>物流费</label>
                    <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>             
            </div>                   
            <div class='row'>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control post_coding" id="post[0][post_coding]" name='post[0][post_coding]' value="">
                </div>
               
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control postage" id="post[0][postage]" placeholder="物流费" name='post[0][postage]' value="">
                </div>
            </div>   
        </div>
    </div> 
         
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
    @endforeach

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModala" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" width="800px">
   <div class="modal-dialog" style="width:800px">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               查询物流单号
            </h4>
         </div>
        <div style="text-align: center">
            <iframe name="kuaidi100" src="http://www.kuaidi100.com/frame/app/index2.html" width="800" height="400" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>
        </div>
      </div>
      
</div>
</div>

@endif

@section('doAction')
    <div class="row">
        <div class="col-lg-12">
            <button class="examine" value="edit">批量审核</button>
        </div>
    </div>
@stop

@stop

@section('childJs')
    <script type='text/javascript'>
	$("#myModal").click(function(){
        //$("#ajaxcode").val(1234);
    })
	$(".setPurchaseOrder div").each(function(){ alert($(this).attr("data-id")); }); 

	$(".hexiao").click(function(){
        if (confirm("确认核销?")) {
            var url = $(this).data('url');
            window.location.href=url;
        }
    })


    $(".tuihui").click(function(){
        if (confirm("确认退回?")) {
            var url = $(this).data('url');
            window.location.href=url;
        }
    })

    $(".daihexiao").click(function(){
        if (confirm("确认待核销?")) {
            var url = $(this).data('url');
            window.location.href=url;
        }
    })

    $(".fukuan").click(function(){
        if (confirm("确认付款?")) {
            var url = $(this).data('url');
            window.location.href=url;
        }
    })
	//批量输入采购单号
	function batchPostCoding(){
		 var batch_post_coding=$('#batch_post_coding').val(); 
			$(".itemPostCoding").val(batch_post_coding);
		}
		//新增物流号对应物流费
        $(document).ready(function () {
            var current = $('#currrent').val();
            $('.addItem').click(function () {
                $.ajax({
                    url: "{{ route('postAdd') }}",
                    data: {current: current},
                    dataType: 'html',
                    type: 'get',
                    success: function (result) {
                        $('#itemDiv').append(result);
                    }
                });
                current++;
            });

 			$(document).on('click', '.bt_right', function () {
				if(current >1) {
                $(this).parent().remove();
                current--; 
                }
            });
           
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

        $('.examine').click(function () {
            
            var url = "{{route('purchaseExmaine')}}";

            var checkbox = document.getElementsByName("tribute_id");
            var purchase_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                purchase_ids += checkbox[i].value + ",";
            }
            purchase_ids = purchase_ids.substr(0, (purchase_ids.length) - 1);

            $.ajax({
                url: url,
                data: {purchase_ids:purchase_ids},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    window.location.reload();
                }
            })
        });
    </script>
@stop