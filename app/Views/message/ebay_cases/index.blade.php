@extends('common.table')
@section('tableToolButtons')@stop

@section('tableHeader')

    <th class="sort" data-field="id">ID</th>
    <th>CaseID</th>
    <th>标题</th>
    <th>status</th>
    <th>type</th>
    <th>买家ID</th>
    <th>卖家ID</th>
    <th>交易号</th>
    <th>创建时间</th>
    <th>操作</th>

@stop
@section('tableBody')
    @foreach($data as $case)
        <tr>
            <td>{{$case->id}}</td>
            <td>{{$case->case_id}}</td>
            <td>{{$case->item_title}}</td>
            <td>{{$case->status}}</td>
            <td>{{$case->type}}</td>
            <td>{{$case->buyer_id}}</td>
            <td>{{$case->seller_id}}</td>
            <td>{{$case->transaction_id}}</td>
            <td>{{$case->creation_date}}</td>
            <td>
                <a href="ebayCases/{{$case->id}}/edit" class="btn btn-primary btn-xs">
                    <span class="glyphicon glyphicon-play"></span> 开始处理
                </a>
            </td>
        </tr>
    @endforeach

@stop
