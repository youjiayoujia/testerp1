@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">    
        <div class="panel-body">
           <div class="form-group col-lg-2">
                <strong>采购单号</strong>       
                <input class="form-control" id="p_id" placeholder="采购单号" name='post_coding' value="">
            </div>
            <div class="form-group purchase">

            </div>	
        </div> 
    </div>  

@stop

@section('pageJs')
<script type='text/javascript'>
    $(document).ready(function(){
    	javascript:document.getElementById("p_id").focus();
    	$(document).on('keydown', function (event) {
    	    if(event.keyCode == '13') {
    	    	$.ajax({
                    url: "{{ route('ajaxRecieve') }}",
                    data: {id: $("#p_id").val()},
                    dataType: 'html',
                    type: 'get',
                    success: function (result) {
                        $(".purchase").html(result);
                        $("#p_id").val("");
                    }
                });
    	    }
    	});
    })


	$(document).on('click','.modify',function(){
		var inputarr = $("input[name^='arrivenum_']");
        var data = ""
		$("input[name^='arrivenum_']").each(function(){
			id = $(this).attr("name");
            id = id.substr(10);
            data += id+":"+$(this).val()+",";
　　　　});

        $.ajax({
            url:"{{ route('updateArriveNum') }}",
            data:{data:data,p_id:$("#p_id").val()},
            dataType:'json',
            type:'get',
            success:function(result){
                $("#p_id").val(result);
                javascript:document.getElementById("p_id").focus();
                var e = jQuery.Event("keydown");//模拟一个键盘事件
                e.keyCode =13;//keyCode=13是回车
                $("#p_id").trigger(e); 
            }
        });                         
	});

</script>
@stop


