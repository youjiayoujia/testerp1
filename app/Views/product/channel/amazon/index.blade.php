@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-info" id="chose_channel">
            <i class="glyphicon glyphicon-ok-circle"></i> 选中shop
        </a>
    </div>
    
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    
    <th>ID</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $amazonProduct)
        <tr>
            <td>{{ $amazonProduct->product_id }}</td>
            <td>{{ $amazonProduct->created_at }}</td>
            <td>
                <a href="{{ route('amazonProduct.show', ['id'=>$amazonProduct->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('amazonProduct.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $amazonProduct->id }}"
                   data-url="{{ route('amazonProduct.destroy', ['id' => $amazonProduct->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop