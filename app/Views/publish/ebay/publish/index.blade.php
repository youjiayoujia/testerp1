<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-05
 * Time: 14:35
 */
        ?>
@extends('common.table')
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"></th>
    <th class="sort text-center" data-field="id">ID</th>
    <th class="text-center">站点</th>
    <th class="text-center">帐号</th>
    <th class="text-center">ItemID</th>
    <th class="text-center">标题</th>
    <th class="text-center">无货在线</th>
    <th class="text-center">EbaySku</th>
    <th class="text-center">物品名称</th>
    <th class="text-center">Local</th>
    <th class="sort text-center" data-field="start_time">刊登时间</th>
    <th class="text-center">刊登人</th>
    <th class="text-center">是否在线</th>
    <th class="sort text-center" data-field="start_price">价格</th>
    <th class="text-center">运费</th>
    <th class="sort text-center" data-field="quantity_sold">销量</th>
    <th class="text-center">在线数量</th>
    <th class="text-center">PayPal</th>
    <th class="text-center">处理天数</th>
    {{--
        <th class="sort" data-field="created_at">创建时间</th>
    --}}
    {{--<th>日志</th>--}}
@stop

@section('tableBody')
    @foreach($data as $detail)
        <tr class="text-center">
            <td><input type='checkbox' name='tribute_id'  value="{{ $detail->id }}"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
@stop