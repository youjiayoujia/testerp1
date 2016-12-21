@extends('common.table')
@section('tableToolButtons')

    <div class="btn-group">
        <form method="POST" action="{{ route('purchaseOrderConfirmCsvFormatExecute') }}" enctype="multipart/form-data" id="add-lots-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="file" class="file" id="qualifications" placeholder="上传采购核销" name="excel" value="">
        </form>

    </div>
    <div class="btn-group">
        <a href="javascript:" class="btn btn-warning download-csv">CSV格式
            <i class="glyphicon glyphicon-arrow-down"></i>

        </a>
        <a class="btn btn-success add-lots-of-purchase-confirm" href="javascript:void(0);">
            <i class="glyphicon glyphicon-plus"></i> 导入数据
        </a>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th class="sort" data-field="id">ID</th>
    <th>采购单</th>
    <th>核销状态</th>
    <th>需核销金额</th>
    <th>实际核销金额</th>
    <th>核销原因</th>
    <th>退款凭据</th>
    <th>退款时间</th>
    <th>核销时间</th>
    <th>导入人</th>
    <th class="sort" data-field="created_at">导入时间</th>
    <th>采购人</th>
@stop
@section('tableBody')
    @foreach($data as $_data)
        <tr>
            <td>{{$_data->id}}</td>
            <td>{{$_data->po_id}}</td>
            <td>
                @if($_data->status==2)<span style='color:red'>{{config('purchase.purchaseOrder.confirm_write_off')[$_data->status]}}</span>@endif
                @if($_data->status==1)<span style='color:green'>{{config('purchase.purchaseOrder.confirm_write_off')[$_data->status]}}</span>@endif
                @if($_data->status==3)<span style='color:red'>{{config('purchase.purchaseOrder.confirm_write_off')[$_data->status]}}</span>@endif
            </td>
            <td>{{$_data->no_delivery_money}}</td>
            <td>{{$_data->real_money}}</td>
            <td>{{$_data->reason}}</td>
            <td>{{$_data->credential}}</td>
            <td>{{$_data->refund_time}}</td>
            <td>{{$_data->updated_at}}</td>
            <td>{{$_data->createUser->name}}</td>
            <td>{{$_data->created_at}}</td>
            <td>{{$_data->purchaseUser->name}}</td>
        </tr>
    @endforeach
@stop
@section('childJs')
    <script>
        $(document).ready(function () {
            //批量导出
            $('.download-csv').click(function(){
                location.href="{{ route('purchaseOrderConfirmCsvFormat')}}";
            });
            //采购核销csv
            $('.add-lots-of-purchase-confirm').click(function () {
                var csv = $('input[name="excel"]').val();
                if(csv == ''){
                    alert('请先上传文件！');
                    return false;
                }
                $('#add-lots-form').submit();
            });
        });
    </script>
@stop
