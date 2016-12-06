@extends('common.form')
@section('formAction') @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID:</label>
            <input type='text' class='form-control modelId' value="{{ $model->id }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>调出仓库</label>
            <input type='text' class='form-control' value="{{ $model->outWarehouse ? $model->outWarehouse->name : '' }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>调入仓库</label>
            <input type='text' class='form-control' value="{{ $model->inWarehouse ? $model->inWarehouse->name : '' }}">
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-2'>
            <input type='text' class='form-control searchsku' placeholder='sku'>
        </div>
        <div class='form-group col-lg-8'>
            <div class='col-lg-2'>
                <button type='button' class='btn btn-info search'>确认</button>
                <button type='button' class='btn btn-warning createbox'><i class="glyphicon glyphicon-plus"></i> 新建装箱信息</button>
            </div>
            <div class='col-lg-2'>
                <input type='text' class='form-control boxnum' placeholder='箱号'>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-8'>
            <font color='red' size='7px' class='notFindSku'></font>
        </div>
    </div>
    <div class='row box' data-flag='false'>
        
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">已扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-3'>注意事项</td>
                    <td class='col-lg-2'>箱号</td>
                    <td class='col-lg-2'>数量</td>
                    <td class='col-lg-2'>按钮</td>
                </thead>
                <tbody class='new'>
                
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">未扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-2'>ID</td>
                    <td class='col-lg-2'>sku</td>
                    <td class='col-lg-2'>注意事项</td>
                    <td class='col-lg-1'>应拣数量</td>
                    <td class='col-lg-1'>实拣数量</td>
                    <td class='col-lg-2'>状态</td>
                </thead>
                <tbody class='old'>
                @foreach($forms as $key => $form)
                    <tr data-id="{{ $form->id }}" data-weight="{{ $form->item ? $form->item->weight : 0}}">
                        <td class='col-lg-2'>{{ $form->id }}</td>
                        <td class='col-lg-2 sku' data-id="{{ $form->item_id}}">{{ $form->item ? $form->item->sku : '' }}</td>
                        <td class='col-lg-2 remark'>{{ $form->item ? $form->item->remark : '' }}</td>
                        <td class='col-lg-1 quantity'>{{ $form->quantity}}</td>
                        <td class='col-lg-1 inboxed_quantity'>{{ $form->inboxed_quantity }}</td>
                        <td class='col-lg-2 status'>
                        @if($form->inbox_quantity != $form->quantity)
                        <font color='red'>包装中</font></td>
                        @else
                        <font>数量已匹配</font>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="box_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">箱子信息</div>
                    <div class="panel-body">
                        <div class='form-group col-lg-6'>
                            <label>体积(cm3):</label>
                            <input type='text' class='form-control box_volumn' name='volumn' placeholder='a*v*c'>
                        </div>
                        <div class='form-group col-lg-6'>
                            <label>重量(kg):</label>
                            <input type='text' class='form-control box_actWeight' name='weight'>
                        </div>
                        <div class='form-group col-lg-6'>
                            <a href="javascript:" class='btn btn-info box_sub'>提交</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <iframe id='barcode' style='display:none'></iframe>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">包装完成</button>
    <button type="reset" class="btn btn-default">取消</button>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).on('keypress', function (event) {
    if(event.keyCode == '13') {
        $('.search').click(); 
        return false;
    }
});

$(document).ready(function(){
    $(document).on('click', '.box_sub', function(){
        volumn = $('.box_volumn').val();
        weight = $('.box_actWeight').val();
        boxId = $('.boxId').val();
        $.get(
            "{{ route('test2')}}",
            {volumn:volumn,weight:weight,boxId:boxId},
            function(result){
                $('.box_volumn').val('');
                $('.box_actWeight').val('');
            });
        $('.box_info').click();
    });

    $(document).on('click', '.cz', function(){
        block = $(this).parent().parent();
        id = block.data('id');
        boxId = $('.boxId').val();
        itemId = block.data('itemid');
        sku = block.find('.sku').text();
        weight = block.data('weight');
        $.get("{{route('test2')}}",
          {id:id, boxId:boxId, itemId:itemId},
          function(result){
            if(result) {
                $('.box_quantity').val(parseInt($('.box_quantity').val()) - 1);
                $('.box_weight').val(parseFloat($('.box_weight').val()) - parseFloat(weight));
                $.each($('.old tr'), function(){
                    tmp = $(this);
                    if(tmp.find('.sku').text() == sku) {
                        tmp.find('.inbox_quantity').text(parseInt(tmp.find('.inbox_quantity').text()) - 1);
                    }
                });
            }
        });
        block.remove();
    });

    $(document).on('click', '.createbox', function(){
        id = $('.modelId').val();
        boxnum = $('.boxnum').val();
        if(!boxnum) {
            $.get("{{route('overseaBox.createbox')}}",
                {id:id},
                function(result){
                    if(result) {
                        $('.boxnum').val(result);
                    } else {
                        alert('箱子创建失败');
                    }
                });
        } else {
            if(confirm('确认新建箱子信息')) {
                $.get("{{route('overseaBox.createbox')}}",
                    {id:id},
                    function(result){
                        if(result) {
                            $('.boxnum').val(result);
                        } else {
                            alert('箱子创建失败');
                        }
                    });
            }
        }
    });

    $(document).on('click', '.search', function(){
        val = $('.searchsku').val();
        $('.notFindSku').text('');
        $('.searchsku').val('');
        extern_flag = 0;
        $('.searchsku').focus();
        if(val) {
            if(!$('.boxnum').val()) {
                alert('请新建装箱信息');
                return false;
            }
            $.each($('.old tr'), function(){
                tmp = $(this);
                inbox_quantity = parseInt(tmp.find('.inbox_quantity').text());
                quantity = parseInt(tmp.find('.quantity').text());
                if(tmp.find('.sku').text() == val && quantity >  inbox_quantity) {
                    extern_flag = 1;
                    tmp.find('.inbox_quantity').text(parseInt(tmp.find('.inbox_quantity').text()) + 1);
                    if(parseInt(tmp.find('.inbox_quantity').text()) == quantity) {
                        tmp.find('.status').text('数量已匹配')
                    }
                    
                    arr = new Array();
                    i=0;
                    str = '';
                    $.each($('.old tr'), function(){
                        if($(this).data('id') == id) {
                            arr[i] = "<td class='sku'>"+$(this).find('.sku').text()+"</td><td class='remark'>"+$(this).find('.remark').text()+"</td><td class='quantity'>1</td>";
                            i++;
                        }
                    });
                    len = arr.length;
                    for(j=0;j<len;j++) {
                        str = "<tr data-id='" + tmp.data('id') + "' data-itemId='"+tmp.find('.sku').data('id')+"' data-boxid='"+$('.modelId').val()+"' data-weight='"+tmp.data('weight')+"'>" + arr[j] + "<td class='col-lg-1'><button type='button' class='cz btn btn-info'>撤销</button></td></tr>";
                    }
                    $('.new').append(str);
                    return false;
                }
            });
        }
        if(out_js) {
            return false;
        }
        if(!extern_flag) {
            $('.notFindSku').text('sku不存在或者该对应的拣货单上sku已满');
        }
    });
});
</script>
@stop