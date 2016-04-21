@extends('common.table')
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th class="sort" data-field="type_id">物流方式</th>
    <th class="sort" data-field="priority">优先级</th>
    <th class="sort" data-field="weight_from">重量从(kg)</th>
    <th class="sort" data-field="weight_to">重量至(kg)</th>
    <th class="sort" data-field="order_amount">订单金额($)</th>
    <th>是否通关</th>
    <th>国家</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $rule)
        <tr>
            <td>{{ $rule->id }}</td>
            <td>{{ $rule->logistics->type }}</td>
            <td>{{ $rule->priority }}</td>
            <td>{{ $rule->weight_from }}</td>
            <td>{{ $rule->weight_to }}</td>
            <td>{{ $rule->order_amount }}</td>
            <td>{{ $rule->is_clearance == '1' ? '是' : '否' }}</td>
            <td>{{ $rule->country }}</td>
            <td>{{ $rule->updated_at }}</td>
            <td>{{ $rule->created_at }}</td>
            <td>
                <a href="{{ route('logisticsRule.show', ['id'=>$rule->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('logisticsRule.edit', ['id'=>$rule->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $rule->id }}"
                   data-url="{{ route('logisticsRule.destroy', ['id' => $rule->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop