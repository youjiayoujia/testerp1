@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('pickList.packageStore', ['id'=>$model->id]) }} @stop
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
    <div class="panel panel-default">
        <div class="panel-heading">已扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-2'>package ID</td>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-3'>注意事项</td>
                    <td class='col-lg-1'>应拣数量</td>
                    <td class='col-lg-1'>实拣数量</td>
                    <td class='col-lg-1'>状态</td>
                    <td class='col-lg-1'>按钮</td>
                </thead>
                <tbody class='new'>
                @foreach($packages as $package)
                    @if($package->has_pick)
                        @foreach($package->items as $key => $packageitem)
                            <tr data-id="{{ $package->id}}" class="{{ $package->id}}">
                                @if($key == '0')
                                <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-2'>{{ $package->id }}</td>
                                @endif
                                <td class='sku col-lg-3'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                                <td class='col-lg-3'>{{ $packageitem->item ? $packageitem->item->remark : '' }}</td>
                                <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                                <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                                @if($key == '0')
                                    @if($package->status != 'PACKED')
                                    <td class='status col-lg-1' rowspan="{{$package->items()->count()}}"><font color='red'>{{ $package->status ? $package->status_name : '' }}</font></td>
                                    @else
                                    <td class='status col-lg-1' rowspan="{{$package->items()->count()}}">{{ $package->status ? $package->status_name : '' }}</td>
                                    @endif
                                @endif
                                @if($key == '0')
                                <td class='col-lg-1' rowspan="{{$package->items()->count()}}"><button type='button' class='cz btn btn-info'>撤销</button></td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">未扫描信息</div>
        <div class="panel-body">
            <table class='table table-bordered table-condensed'>
                <thead>
                    <td class='col-lg-2'>package ID</td>
                    <td class='col-lg-3'>sku</td>
                    <td class='col-lg-3'>注意事项</td>
                    <td class='col-lg-1'>应拣数量</td>
                    <td class='col-lg-1'>实拣数量</td>
                    <td class='col-lg-1'>状态</td>
                </thead>
                <tbody class='old'>
                @foreach($packages as $package)
                    @if(!$package->has_pick)
                        @foreach($package->items as $key => $packageitem)
                            <tr data-id="{{ $package->id}}" class="{{ $package->id}}">
                                @if($key == '0')
                                <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-2'>{{ $package->id }}</td>
                                @endif
                                <td class='sku col-lg-3'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                                <td class='col-lg-3'>{{ $packageitem->item ? $packageitem->item->remark : '' }}</td>
                                <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                                <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                                @if($key == '0')
                                <td class='status col-lg-1' rowspan="{{$package->items()->count()}}"><font color='red'>{{ $package->status ? $package->status_name : '' }}</font></td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class='already'>
    <label>已扫描</label>
    </div><hr/>
    <div class='old2'>
    <label>未扫描</label>
    </div>
    <div class='row'>
        <iframe id='barcode' style='display:none'></iframe>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">包装完成</button>
    <button type="reset" class="btn btn-default">取消</button>
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
                $('.old2').append(str2);
            }
        });
    });

    $(document).on('click', '.cz', function(){
        block = $(this).parent().parent();
        packageId = block.data('id');
        $.get("{{route('package.ctrlZ')}}",
          {packageId:packageId},
          function(result){
            if(result) {
                location.reload();
            }
        });
    });

    $(document).on('click', '.search', function(){
        val = $('.searchsku').val();
        extern_flag = 0;
        $('.notFindSku').text('');
        if(val) {
            $.each($('.new tr'), function(){
                tmp = $(this);
                if(tmp.find('.sku').text() == val) {
                    picked_quantity = parseInt(tmp.find('.picked_quantity').text());
                    quantity = parseInt(tmp.find('.quantity').text());
                    if(quantity > picked_quantity) {
                        extern_flag = 1;
                        package_id = tmp.data('id');
                        sku = tmp.find('.sku').text();
                        $.ajax({
                            url:"{{ route('pickList.packageItemUpdate')}}",
                            data:{package_id:package_id, sku:sku},
                            dataType:'json',
                            type:'get',
                            success:function(result) {
                            }
                        });
                        tmp.find('.picked_quantity').text(picked_quantity + 1);
                        if(parseInt(tmp.find('.picked_quantity').text()) == quantity) {
                            needId = tmp.data('id');
                            flag = 1;
                            $.each($('.new tr'), function(){
                                innerNeedId = $(this).data('id');
                                if(innerNeedId == needId) {
                                    if(parseInt($(this).find('.picked_quantity').text()) != parseInt($(this).find('.quantity').text())) {
                                        flag = 0;
                                    }
                                }
                            });
                            if(flag) {
                                id = tmp.data('id');
                                $("."+id).find('.status').text('已包装');
                                $('#barcode').attr('src', ("{{ route('templateMsg', ['id'=>''])}}/"+package_id));
                                $('#barcode').load(function(){
                                    $('#barcode')[0].contentWindow.focus();
                                    $('#barcode')[0].contentWindow.print();
                                });
                            }
                        }
                    exit;
                    }
                }
            });
            $.each($('.old tr'), function(){
                tmp = $(this);
                old_flag = 0;
                if(tmp.find('.sku').text() == val && parseInt(tmp.find('.quantity').text()) >  parseInt(tmp.find('.picked_quantity').text())) {
                    old_flag = 1;
                    tmp.find('.picked_quantity').text(parseInt(tmp.find('.picked_quantity').text()) + 1);
                    if(parseInt(tmp.find('.picked_quantity').text()) == parseInt(tmp.find('.quantity').text())) {
                        needId = tmp.data('id');
                        flag = 1;
                        $.each($('.old tr'), function(){
                            innerNeedId = $(this).data('id');
                            if(innerNeedId == needId) {
                                if(parseInt($(this).find('.picked_quantity').text()) != parseInt($(this).find('.quantity').text())) {
                                    flag = 0;
                                }
                            }
                        });
                        if(flag) {
                            tmp.find('.status').text('已包装');
                            $('#barcode').attr('src', ("{{ route('templateMsg', ['id'=>''])}}/"+package_id));
                            $('#barcode').load(function(){
                                $('#barcode')[0].contentWindow.focus();
                                $('#barcode')[0].contentWindow.print();
                            });
                        }
                    }
                    package_id = tmp.data('id');
                    sku = tmp.find('.sku').text();
                    $.ajax({
                        url:"{{ route('pickList.packageItemUpdate')}}",
                        data:{package_id:package_id, sku:sku},
                        dataType:'json',
                        type:'get',
                        success:function(result) {
                        }
                    });
                    needId = tmp.data('id');
                    arr = new Array();
                    i=0;
                    str = '';
                    $.each($('.old tr'), function(){
                        if($(this).data('id') == needId) {
                            arr[i] = $(this).html();
                            $(this).remove();
                            i++;
                        }
                    });
                    len = arr.length;
                    for(j=0;j<len;j++) {
                        if(j == 0) {
                            str = "<tr data-id='" + tmp.data('id') + "' class='"+ tmp.data('id') +"'>" + arr[j] + "<td class='col-lg-1' rowspan='" + len + "'><button type='button' class='cz btn btn-info'>撤销</button></td></tr>";
                        } else {
                            str += "<tr data-id='" + tmp.data('id') + "' class='"+ tmp.data('id') + "'>" + arr[j] + "</tr>";
                        }
                    }
                    $('.new').append(str);
                }
                if(old_flag) {
                    exit;
                }
            });
        }
        if(!extern_flag) {
            $('.notFindSku').text('sku不存在或者该对应的拣货单上sku已满');
        }
        $('.searchSku').val('');
        $('.searchSku').focus();
    });

    function do_print(id_str)
    {
        var el = document.getElementById(id_str);
        if(el.attachEvent) {
            el.attachEvent("onload", function(){
                el.contentWindow.focus();
                el.contentWindow.print();
            });
        } else {
            el.onload = function(){
                el.contentWindow.focus();
                el.contentWindow.print();
            }
        }
    }
});
</script>