<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
<br><br><br>
<!-- Table goes in the document BODY -->
<table class="gridtable">
	<tr>
		<th colspan="3">查看采购单详情：采购单号{{$id}}</th>
	</tr>
	<tr>
		<td>下单时间</td>
		<td>{{$purchase_order->created_at}}</td>
	</tr>
	<tr>
		<td>付款方式</td>
		<td>{{$purchase_order->pay_type}}</td>
	</tr>
	<tr>
		<td>付款凭证</td>
		<td></td>
	</tr>
	<tr>
		<td>入库仓库</td>
		<td>{{$purchase_order->warehouse->name}}</td>
	</tr>
	<tr>
		<td>订单详情</td>
		<td>
			<table class="gridtable">
			<tr>
				<th>图片预览</th>
				<th>SKU</th>
				<th>名称</th>
				<th>注意事项</th>
				<th>采购数量</th>
				<th>已到货</th>
				<th>实到</th>
				<th>打印条码</th>
			</tr>
			@foreach($purchase_order->purchaseItem as $keys=>$item)
				<tr>
					<td><img src=""></td>
					<td>{{$item->sku}}</td>
					<td>{{$item->item->name}}</td>
					<td></td>
					<td>{{$item->purchase_num}}</td>
					<td>{{$item->arrival_num}}</td>
					<td><input type="text" value="0" name="arrivenum_{{$item->id}}"></td>
					<td><button>打印</button></td>
				</tr>
			@endforeach
			</table>
		</td>
	</tr>
	<tr>
		<td>订单运费</td>
		<td>NULL</td>
	</tr>
	<tr>
		<td>订单总金额</td>
		<td>{{$purchase_order->total_purchase_cost}}</td>
	</tr>
	<tr>
		<td>订单备注</td>
		<td>{{$purchase_order->pay_type}}</td>
	</tr>
	<tr>
		<td>订单采购员</td>
		<td>{{$purchase_order->assigner_name}}</td>
	</tr>
	<tr>
		<td>收货记录</td>
		<td>
			<table class="gridtable">
				<tr>
					<td>序号</td>
					<td>编码</td>
					<td>到货</td>
					<td>时间</td>
					<td>优品</td>
					<td>误差</td>
					<td>质检</td>
					<td>跟踪</td>
				</tr>
			@foreach($purchase_order->purchaseItem as $item)
				@foreach($item->arrivalLog as $log_key=>$log)
					<tr>
						<td>{{$log_key+1}}</td>
						<td>{{$log->sku}}</td>
						<td>{{$log->arrival_num}}</td>
						<td>{{$log->created_at}}</td>
						<td>{{$log->good_num}}</td>
						<td>{{$log->bad_num}}</td>
						<td>{{$log->quality_time}}</td>
						<td></td>
					</tr>
				@endforeach
			@endforeach
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<button class="modify">修改</button>
			<button>设置全部到货</button>
		</td>
	</tr>
</table>


