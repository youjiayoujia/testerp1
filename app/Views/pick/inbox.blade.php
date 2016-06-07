@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('pickList.inboxStore', ['id'=>$model->id]) }} @stop
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID</label>
            <input type='text' class='form-control' value={{ $model->picknum }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>类型</label>
            <input type='text' class='form-control' value={{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>状态</label>
            <input type='text' class='form-control' value={{ $model->status_name }}>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-2'>
            <input type='text' class='form-control searchsku' placeholder='sku'>
        </div>
        <div class='form-group col-lg-2'>
            <button type='button' class='btn btn-info search'>确认</button>
            <button type='button' class='btn btn-warning printException'>打印异常</button>
        </div>
        <div class='form-group col-lg-8'>
            <font color='red' size='7px' class='notFindSku'></font>
        </div>
    </div>
    <div class='row'>
        <div class='form-group result col-lg-6 inboxNum'>
            
        </div>
        <div class='col-lg-6 inboxImage image'>
            
        </div>
    </div>
    <table class='table table-bordered'>
        <thead>
            <td class='col-lg-2'>package ID</td>
            <td class='col-lg-6'>sku</td>
            <td class='col-lg-1'>应拣数量</td>
            <td class='col-lg-1'>实拣数量</td>
            <td class='col-lg-2'>状态</td>
        </thead>
        <tbody>
        @foreach($packages as $k => $package)
            <table class='table table-bordered table-condensed'>
            @foreach($package->items as $key => $packageitem)
                <tr>
                    @if($key == '0')
                    <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-2' name="{{ $k+1 }}">{{ $package->id }}</td>
                    @endif
                    <td class='sku col-lg-6'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                    <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                    <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                    @if($key == '0')
                    <td class='status col-lg-2' rowspan="{{$package->items()->count()}}"><font color='red'>{{ $package->status ? $package->status_name : '' }}</font></td>
                    @endif
                </tr>
            @endforeach
            </table>
        @endforeach
        </tbody>
    </table>
    <div class='already'>
    <label>已扫描</label>
    </div><hr/>
    <div class='old'>
    <label>未扫描</label>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">拣货完成</button>
@stop
<script type='text/javascript'>
$(document).on('keypress', function (event) {
    if(event.keyCode == '13') {
        $('.search').trigger("click"); 
        return false;
    }
});

$(document).ready(function(){
    $('.printException').click(function(){
        $.each($('.sku'), function(){
            tmp = $(this).parent();
            sku = $(this).text();
            picked_quantity = parseInt(tmp.find('.picked_quantity').text());
            quantity = parseInt(tmp.find('.quantity').text());
            if(picked_quantity) {
                str1 = "<p>" + sku + '    ' + picked_quantity + "</p>";
                $('.already').append(str1); 
            }
            if(quantity > picked_quantity) {
                str2 = "<p>" + sku + '    ' + (quantity - picked_quantity) + "</p>";
                $('.old').append(str2);
            }
        });
    });

    $(document).on('click', '.search', function(){
        val = $('.searchsku').val();
        if(val) {
            $('.result').html('');
            $('.notFindSku').html('');
            $('.image').html('');
            outflag = 0;
            $.each($('.sku'), function(){
                if($(this).text() == val) {
                    row = $(this).parent();
                    block = row.parent();
                    if(parseInt(row.find('.quantity').text()) > parseInt(row.find('.picked_quantity').text())) {
                        outflag = 1;
                        row.find('.picked_quantity').text(parseInt(row.find('.picked_quantity').text())+1);
                        img = 0;
                        $.get("{{route('item.getImage')}}",
                              {sku:block.find('.sku').text()},
                              function(result){
                                if(result) {
                                    str = "<h1>"+block.find('.package_id').attr('name')+"</h1>";
                                    str += "<h2>sku:"+block.find('.sku').text()+"</h2>";
                                    $('.image').html("<img class='inboxImage' src="+result+">");
                                    img = 1;
                                }
                            });
                        if(img == 0) {
                            $('.result').html(block.find('.package_id').attr('name'));
                        }
                        if(parseInt(row.find('.quantity').text()) == parseInt(row.find('.picked_quantity').text())) {
                            flag = '1';
                            $.each(block.find('.picked_quantity'), function(){
                                if(parseInt($(this).text()) != parseInt($(this).parent().find('.quantity').text())) {
                                    flag = '0';
                                }
                            });
                            if(flag == '1') {
                                block.find('.status').text('拣货完成');
                            }
                        }
                    return 2;
                    }
                }
            });
            if(outflag == 0) {
                $('.notFindSku').text("sku不存在或者对应的拣货单上sku已满");
            }
            $('.searchSku').val('');
            $('.searchSku').focus();
        }
    });

    //阻止表单通过回车键提交
    // $(document).on("keypress", "input", function (e) {
    //     var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
    //     if (keyCode == 13) {
    //         for (var i = 0; i < this.form.elements.length; i++) {
    //             if (this == this.form.elements[i]) break;
    //         }
    //         i = (i + 1) % this.form.elements.length;
    //         this.form.elements[i].focus();
    //         return false;
    //     } else {
    //         return true;
    //     }
    // });
});
</script>