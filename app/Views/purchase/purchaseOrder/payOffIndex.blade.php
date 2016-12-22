@extends('common.detail')
@section('detailBody')
    <form action="{{ route('purchaseOrder.excelPayOffExecute') }}" method="post" enctype="multipart/form-data">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
         <input type="file" name="upload" >
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary" >
               批量付款
            </button>
         </div>
     </form>


    <div class="panel panel-default">
        <div class="panel-heading">操作结果:</div>
        <div class="panel-body"> 
                <table class="gridtable" align="center" valign="center">
                    <tr>
                        <th width=600>采购单号</th>
                        <th width=600>付款状态</th>
                    </tr>
                    @if(count($data))
                        @foreach($data as $_data)
                            <tr>
                                <td>{{$_data['id']}}</td>
                                <td>@if($_data['status'])<span style="color:green">成功</span>@else<span style="color:red">失败</span>@endif</td>
                            </tr>   
                        @endforeach
                    @endif    
                </table>
            
        </div> 
    </div>

    

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
@stop