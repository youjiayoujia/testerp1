@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" >
        <a href="/purchaseOrder/purchaseOrdersOut" class="btn btn-info" id="orderExcelOut"> 采购单导出
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
	
    <th>ID</th> 
    <th>采购单信息</th> 
    <th>采购单审核状态</th>
    <th>核销状态</th>
    <th>采购人</th>
   	<th>供应商</th>
    <th>采购物品</th>
    <th>采购去向</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $purchaseOrder)
        <tr>
       		
            <td>单据号：NO.{{$purchaseOrder->id }}</br>
            	付款方式：{{$purchaseOrder->supplier->pay_type}}</br>
               外部单号：@if($purchaseOrder->purchase_post_num > 0) {{$purchaseOrder->purchase_post->post_coding}} @else 暂无单号 @endif
            </td>
           <td> @foreach(config('purchase.purchaseOrder.status') as $k=>$statu)
            	@if($purchaseOrder->status == $k)
            	{{ $statu }}
                @endif
            @endforeach </td>
            @foreach(config('purchase.purchaseOrder.examineStatus') as $k=>$statu)
            	@if($purchaseOrder->examineStatus == $k)
            	<td>{{ $statu }}</td>
                @endif
            @endforeach   
            <td>{{config('purchase.purchaseOrder.write_off')[$purchaseOrder->write_off]}}</td>  
    		<td>{{ $purchaseOrder->assigner_name }}
            </td>
            <td>
            
            @if($purchaseOrder->supplier_id >0)
            @foreach(config('purchase.purchaseOrder.close_status') as $k=>$close_statu)
            	@if($purchaseOrder->close_status == $k)
            	{{ $close_statu}}
                @endif
            @endforeach
            	</br>供应商编号NO.{{ $purchaseOrder->supplier->id}}
            @endif
            </td>
            <td>
            @if($purchaseOrder->status <4)
                <table class="table table-bordered table-striped table-hover sortable">
                <thead>
                <tr>
                <th >sku</th>
                <th>名称</th>
                <th>采购数量</th>
                <th>已到货数量</th>
                <th>入库数量</th>
                <th>不合格</th>
                <th>预计到货日期</th>
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
                <td>{{$purchase_item->item->c_name}}</td>
                <td>{{$purchase_item->purchase_num}}</td>
                <td>{{$purchase_item->arrival_num}}</td>
                <td>{{$purchase_item->storage_qty}}</td>
                <td>{{$purchase_item->active_num}}</td>
                <td>{{$purchase_item->start_buying_time}}</td>
                <td>{{$purchase_item->arrival_time}}</td>
                <td>
                 @foreach(config('purchase.purchaseItem.status') as $key=>$status)
                {{$purchase_item->status == $key ? $status : ''}}
                @endforeach
                </td>
                <td>{{$purchase_item->purchase_cost}}</td>
                <td>{{$purchase_item->item->purchase_price}}</td>
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
                <th>{{ $purchaseOrder->sum_purchase_account}}</th>
                <th>{{ $purchaseOrder->sum_purchase_storage_account}}</th>
                <th>&nbsp;</th>
                </tr>
                </tbody>
                </table>
                @endif
            </td>
            <td>{{ $purchaseOrder->warehouse->name ? $purchaseOrder->warehouse->name : '暂无仓库'}}</td>
                  
            <td>{{ $purchaseOrder->created_at }}</td>
            <td>
            	<a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" title="审核" class="btn btn-info btn-xs">
                     <span class="glyphicon glyphicon-ok-sign"></span>
                </a>
                <a href="{{ route('purchaseOrder.show', ['id'=>$purchaseOrder->id]) }}"  title="详情"  class="btn btn-info btn-xs">
                     <span class="glyphicon glyphicon-eye-open"></span>  
                </a>
                 <a href="{{ route('purchaseOrder.edit', ['id'=>$purchaseOrder->id]) }}" title="修改" class="btn btn-warning btn-xs">
                   <span class="glyphicon glyphicon-pencil"></span>
                </a>
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
                
               <a data-toggle="modal" data-target="#myModal" title="添加物流单号" class="btn btn-info btn-xs setPurchaseOrder" data-id="{{$purchaseOrder->id}}" >
                    <span class="glyphicon glyphicon-plus"></span>
                </a> 
                @if($purchaseOrder->status == 1|| $purchaseOrder->status == 2||$purchaseOrder->status == 3)
                <a data-toggle="modal" data-target="#myModala" title="查询物流单号" class="btn btn-primary btn-xs">
                    <span class="glyphicon glyphicon-zoom-in"></span>
                </a>
                @endif 
                 <a href="/purchaseOrder/cancelOrder/{{$purchaseOrder->id}}" title="退回" class="btn btn-danger btn-xs">
                    <span class="glyphicon glyphicon-remove-sign"></span>
                </a>
				<a href="/purchaseOrder/printOrder/{{$purchaseOrder->id}}" title="打印" class="btn btn-primary btn-xs">
                    <span class="glyphicon glyphicon-print"></span>
                </a>                       
                
            </td>
        </tr>
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
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
                </div>
                 
                 
                    <input type="hidden" id="currrent" value="1">
                     
        </div>
        <!--<div class="panel-footer">
            <div class="addItem create"><i class="glyphicon glyphicon-plus"></i><strong>新增采购单号和物流费</strong></div>
        </div>-->
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
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModala" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
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


@stop

@section('childJs')
    <script type='text/javascript'>
	
	$(".setPurchaseOrder div").each(function(){ alert($(this).attr("data-id")); }); 

	$(".hexiao").click(function(){
        if (confirm("确认核销?")) {
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
    </script>
@stop