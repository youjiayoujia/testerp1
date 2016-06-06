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
             NO.{{$data['purchaseOrder']->id}}
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
       <div class="row">
        <table class="table table-bordered table-striped table-hover sortable">
                <thead>
                <tr>
                <th>sku</th>
                <th>状态</th>
                <th>采购数量</th>
                <th>到货数量</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data['purchaseItems'] as $purchaseItem)
                <tr>
                <td>{{$purchaseItem->sku}}</td>
                <td>
                @foreach(config('purchase.purchaseItem.status') as $key=>$status)
                {{$purchaseItem->status == $key ? $status : ''}}
                @endforeach
                </td>
                <td>{{$purchaseItem->purchase_num}}</td>
                <td>{{$purchaseItem->arrival_num}}</td>
                </tr>
                @endforeach
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
                    }
                });
		   }
	   </script>
       
@stop