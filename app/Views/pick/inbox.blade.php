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
        <div class='form-group'>
            <button type='button' class='btn btn-info search'>确认</button>
        </div>
    </div>
    <div class='form-group result'>
    
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
        @foreach($packages as $package)
            <table class='table table-bordered table-condensed'>
            @foreach($package->items as $key => $packageitem)
                <tr>
                    @if($key == '0')
                    <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-2'>{{ $package->id }}</td>
                    @endif
                    <td class='sku col-lg-6'>{{ $packageitem->items ? $packageitem->items->sku : '' }}</td>
                    <td class='quantity col-lg-1'>{{ $packageitem->quantity}}</td>
                    <td class='picked_quantity col-lg-1'>{{ $packageitem->picked_quantity }}</td>
                    @if($key == '0')
                    <td class='status col-lg-2' rowspan="{{$package->items()->count()}}">{{ $package->status ? $package->status_name : '' }}</td>
                    @endif
                </tr>
            @endforeach
            </table>
        @endforeach
        </tbody>
    </table>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">拣货完成</button>
@stop
<script type='text/javascript'>
$(document).ready(function(){
    $('.search').click(function(){
        val = $('.searchsku').val();
        if(val) {
            outflag = 0;
            $.each($('.sku'), function(){
                if($(this).text() == val) {
                    row = $(this).parent();
                    block = row.parent();
                    if(parseInt(row.find('.quantity').text()) > parseInt(row.find('.picked_quantity').text())) {
                        outflag = 1;
                        row.find('.picked_quantity').text(parseInt(row.find('.picked_quantity').text())+1);
                        $('.result').html(block.find('.package_id').text() - {{$packages->first()->id}} + 1);
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
                    exit;
                    }
                }
            });
            if(outflag == 0) {
                alert('sku不存在或者该对应的拣货单上sku已满');
            }
        }
    });
});
</script>