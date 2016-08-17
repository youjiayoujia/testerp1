@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>账号</th>
    <th>产品ID</th>
    <th>标题</th>
    <th>erp Sku</th>
    <th>smt Sku</th>
    <th>物品名称</th>
    <th>近30天销量</th>
    <th>刊登时间</th>
    <th>刊登人员</th>
    <th>状态</th>
    <th>平台状态</th>
    <th>价格</th>
    <th>订单利润率</th>
    <th>最低价</th>
    <th>折扣率</th>
    <th>有货/无货</th> 
    <th>操作</th>
@stop
@section('tableBody')
      @foreach($data as $item)
      <tr>
        <th></th>
        <th>{{$item->product->accounts->account}}</th>
        <th>{{$item->productId}}</th>
        <th>{{$item->product->subject}}</th>
        <th></th>
        <th></th>
        <th>{{$item->products->c_name}}</th>
        <th></th>
        <th>{{$item->updated_at}}</th>
        <th>{{$item->product->userInfo->name}}</th>
        <th></th>
        <th></th>
        <th>{{$item->skuPrice}}</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
      @endforeach
@stop