@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th>帐号</th>
    <th>物流渠道</th>  
    <th>粉、液、电、物流渠道</th> 
    <th>调价幅度</th>
    <th>限价金额</th>
    <th>调价状态</th>
    <th>创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $item)
        <tr>
           <td><input type="checkbox" name="ids[]" value="{{$item->id}}" /> </td>
           <td>{{$accountInfoArr[$item->token_id]}}</td>
           <td>{{$shipmentArr[$item->shipment_id]}}</td>
           <td>{{$shipmentArr[$item->shipment_id_op]}}</td>
           <td>{{$item->percentage}}%</td>
           <td>{{$item->re_pirce}}</td>
           <td>
                @if($item->stauts == 1)未调价
                @else 已调价
                @endif
           </td>
           <td>{{$item->created_at}}</td>
           <td>
                 <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $item->id }}"
                   data-url="{{ route('smtPriceTask.destroy', ['id' => $item->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除                    
               </a>     
           </td>
        </tr>
    @endforeach
@stop
@section('tableToolButtons')
    <div class="btn-group">
            <a class="btn btn-success batch_operate" href="javascript:void(0)">
               批量删除
            </a>
        </div>    
@stop
@section('childJs')
<script type="text/javascript">
    $(".batch_operate").click(function(){
        if (confirm('确定要批量删除数据吗？')){
            var Ids = $('input[name="ids[]"]:checked').map(function() {
                return $(this).val();
            }).get().join(',');
            if (Ids == ''){
                alert('请勾选需要的数据');
                return false;
            }
            $.ajax({
                url: "{{route('smtPriceTask.batchDelete')}}",
                data: 'Ids='+Ids,
                type: 'POST',
                dataType: 'json',
    
                success: function(data){
                    var str='';
                    if (data.data){
                        $.each(data.data, function(index, el){
                            str += el+';';
                        });
                    }
                    if (data.status) { //成功
                        showxbtips(data.info+str);
                    }else {
                        showxbtips(data.info+str, 'alert-warning');
                    }
                    window.location.reload();
                }
    
    
            });
        }
    });
</script>
@stop