@extends('common.detail')
@section('detailBody')

        
     		<div class="row">
             <div class="form-group col-lg-6">
             <strong>运单号:</strong>
            {{$postCoding}}
            <input type="hidden" id="post_coding" value="{{$postCoding}}">
             </div>
              @if($postcodingNum >0)
             <div class="form-group col-lg-6">
             <strong>已关联采购单:</strong>
             <span id="guanlian">NO.{{$data['purchaseOrder']->id}}</span>
             </div>
             @else
             <div class="form-group col-lg-6">
             <strong>未关联采购单</strong>
             </div>
              @endif
             </div>   
             @if($postcodingNum ==0)
             <div class="row">
             <div class="form-group col-lg-4">
              <strong>增加关联采购单号</strong>
              <input  type="text" name="purchase_order_id" id="purchase_order_id" value="">
            </div>
             <div class="form-group col-lg-4">
             <strong>录入运费</strong>
              <input type="text" name="postage" id="postage" value="">
            </div>
             <div class="form-group col-lg-4">
              <input type="button" value="关联" onClick="binding()">
            </div>
       </div>
       @else
       <div class="row" id="po_{{$data['postcoding']->id}}">
        <table class="table table-bordered table-striped table-hover sortable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>运单号</th>
                    <th>运单状态</th>
                    <th>关联采购单</th>
                    <th>扫描人</th>
                    <th>扫描时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                
                <tr>
                    <td>{{$data['postcoding']->id}}</td>
                    <td>{{$data['postcoding']->post_coding}}</td>
                    <td>已关联</td>
                    <td>{{$data['postcoding']->purchase_order_id}}</td>
                    <td>{{$data['postcoding']->user?$data['postcoding']->user->name:''}}</td>
                    <td>{{$data['postcoding']->updated_at}}</td>
                    <td>
                        <a href="javascript:" class="btn btn-danger btn-xs delete_item" data-id="{{$data['postcoding']->id}}">
                            <span class="glyphicon glyphicon-trash"></span> 删除关联
                        </a>
                    </td>
                </tr>
                
                 </tbody>
                </table>
       </div>
       @endif
   
       <script type="text/javascript">
	    function binding(){
		   var postage = $('#postage').val();
		   var purchaseOrderId = $('#purchase_order_id').val();
		   var postCoding = $('#post_coding').val();
		   $.ajax({
                    url: "{{ route('binding') }}",
                    data: {postage: postage,purchaseOrderId:purchaseOrderId,postCoding:postCoding},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        if(result == 1){
							alert('绑定成功');
						}
                        if(result == 2){
                            alert('绑定失败');
                        }
                    }
            });
		}

        $(".delete_item").click(function(){
            if (confirm("确认删除关联?")) {
                var id = $(this).data("id");
                $.ajax({
                        url: "{{ route('deletePostage') }}",
                        data: {id:id},
                        dataType: 'json',
                        type: 'get',
                        success: function (result) {
                            $("#po_"+id).css("display","none");
                            $("#guanlian").html("");
                        }
                });
            }

        })
	   </script>
       
@stop