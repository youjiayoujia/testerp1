@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
@section('formAction') {{ route('stockAllotment.store') }} @stop
@section('formBody')
    <div class='row'>
        <div class="form-group col-lg-4">
            <label for="allotment_id" class='control-label'>调拨单号</label> 
            <input type='text' class="form-control" id="allotment_id" placeholder="调拨单号" name='allotment_id' value="{{ old('allotment_id') ? old('allotment_id') : 'DB'.time()}}" readonly>
        </div>
        <div class="form-group col-lg-2">
            <label for="allotment_by" class='control-label'>调拨人</label> 
            <input type='text' class="form-control" id="allotment_by" placeholder="调拨人" name='allotment_by' value="{{ old('allotment_by') ? old('allotment_by') : '1' }}" readonly>
        </div>
        <div class="form-group col-lg-3">
            <label for="out_warehouse_id" class='control-label'>调出仓库</label>  <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id='out_warehouse_id' name='out_warehouse_id' class='form-control'>
            <option value=''>请选择仓库</option>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{old('out_warehouse_id') == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="in_warehouse_id" class='control-label'>调入仓库</label>  <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select id='in_warehouse_id' name='in_warehouse_id' class='form-control'>
            <option value=''>请选择仓库</option>
            @foreach($warehouses as $warehouse)
                <option value='{{ $warehouse->id }}' {{old('in_warehouse_id') == $warehouse->id ? 'selected' : ''}}>{{ $warehouse->name }}</option>
            @endforeach
            </select> 
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class='control-label'>备注</label>
        <textarea name='remark' class='form-control'>{{ old('remark') }}</textarea>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            列表
        </div>
        <div class='panel-body'>
            <div class='row'>
                <div class="form-group col-sm-2">
                    <label for="sku">sku</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="warehouse_position_id">库位</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="access_quantity" class='control-label'>可用数量</label>
                </div>
                <div class="form-group col-sm-2">
                    <label for="quantity" class='control-label'>数量</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="amount" class='control-label'>总金额(￥)</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
                </div>
            </div>
            <div class='row'>
                <div class="form-group col-sm-2">
                    <select name='arr[item_id][0]' id='arr[sku][0]' class='form-control sku'>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <select name='arr[warehouse_position_id][0]' id='arr[warehouse_position_id][0]' class='form-control warehouse_position_id'>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control access_quantity" placeholder="可用数量" name='arr[access_quantity][0]' value="{{ old('arr[access_quantity][0]') }}" readonly>
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control quantity" id="arr[quantity][0]" placeholder="数量" name='arr[quantity][0]' value="{{ old('arr[quantity][0]') }}">
                </div>
                <div class="form-group col-sm-2">
                    <input type='text' class="form-control amount" id="arr[amount][0]" placeholder="总金额" name='arr[amount][0]' value="{{ old('arr[amount][0]') }}" readonly>
                </div>
                <button type='button' class='btn btn-danger bt_right'><i class='glyphicon glyphicon-trash'></i></button>
            </div>
            <div class='row addpanel'>
                <a href='javascript:' class='btn btn-primary col-sm-12' id='create_form'>
                    <span class='glyphicon glyphicon-plus'>新增</span>
                </a>
            </div>
        </div>
    </div>  
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        current = 1;
        $(document).on('click', '#create_form', function(){
            warehouse = $('#out_warehouse_id').val();
            $.ajax({
                url:"{{ route('add') }}",
                data:{current:current, warehouse:warehouse},
                dataType:'html',
                type:'get',
                success:function(result) {
                    $('.addpanel').before(result);
                    current++;
                }
            })
        });

        $(document).on('click', '.bt_right', function(){
            $(this).parent().remove();
        });

        $(document).on('change', '#out_warehouse_id', function(){
            warehouse = $('#out_warehouse_id').val();
            obj = $(this).parent();
            position = $('.warehouse_position_id');
            quantity = $('.quantity');
            amount = $('.amount');
            sku = $('.sku');
            $.ajax({
                url: "{{ route('allotoutwarehouse') }}",
                data: {warehouse:warehouse},
                dataType:'json',
                type:'get',
                success:function(result){
                    sku.empty();
                    position.empty();
                    quantity.val('');
                    amount.val('');
                    if(result != 'none') {
                        str = '';
                        str1 = '';
                        for(var i=0;i<result[2].length;i++) 
                        {
                            str += '<option value='+result[2][i]['id']+'>'+result[2][i]['name']+'</option>';
                        }
                        for(var i=0;i<result[0].length;i++)
                        {
                            str1 += '<option value='+result[0][i]['items']['id']+'>'+result[0][i]['items']['sku']+'</option>';
                        }
                        if(result[1]) {
                            $('.access_quantity').val(result[1]['available_quantity']); 
                        } else {
                            $('.access_quantity').val('');
                            sku.empty();
                        }
                        $(str).appendTo(position);
                        $(str1).appendTo(sku);
                    }
                }
            });
        });

        $(document).on('change', '.warehouse_position_id', function(){
            obj = $(this).parent().parent();
            warehouse = $('#out_warehouse_id').val();
            position = $(this).val();
            sku = obj.find('.sku').val();
            $.ajax({
                url:"{{ route('allotposition' )}}",
                data: {position:position, item_id:sku},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result != 'none') {
                        obj.find('.access_quantity').val(result[0]['available_quantity']);
                        if(obj.find('.quantity').val() && obj.find('.quantity').val() > result[0]['available_quantity']) 
                        {
                            alert('数量超过了可用数量');
                            obj.find('.quantity').val('');
                            obj.find('.amount').val('');
                        }
                    } else {
                        obj.find('.access_quantity').val('');
                        obj.find('.item_id').val('');
                        obj.find('.quantity').val('');
                        obj.find('.amount').val('');
                    }
                }
            });
        });

        $(document).on('change', '.sku', function(){
            obj = $(this).parent().parent();
            warehouse = $('#out_warehouse_id').val();
            position = obj.find('.warehouse_position_id');
            sku = $(this).val();
            $.ajax({
                url:"{{ route('allotsku' )}}",
                data: {warehouse:warehouse, item_id:sku},
                dataType:'json',
                type:'get',
                success:function(result) {
                    if(result != 'none') {
                        str = '';
                        position.empty();
                        obj.find('.access_quantity').val(result[0]['available_quantity']);
                        for(var i=0;i<result[1].length;i++) 
                        {
                            str +="<option value="+result[1][i]['id']+">"+result[1][i]['name']+"</option>";
                        }
                        if(obj.find('.quantity').val() && parseInt(obj.find('.quantity').val()) > parseInt(obj.find('.access_quantity').val()))
                        {
                            alert('超出库存数量');
                            obj.find('.quantity').val('');
                            obj.find('.amount').val('');
                            $(str).appendTo(position);
                            return;
                        }
                        if(obj.find('.quantity').val() && parseInt(obj.find('.quantity').val()) < parseInt(obj.find('.access_quantity').val()))
                        {
                            obj.find('.amount').val((result[2]*parseInt(obj.find('.quantity').val())).toFixed('3'));
                        }
                        $(str).appendTo(position);
                    } else {
                        alert('sku对应没有库存');
                        position.empty();
                        obj.find('access_quantity').val('');
                        obj.find('.amount').val('');
                    }
                }
            });
        });

        $(document).on('blur', '.quantity', function(){
            if($(this).val()) {
                var reg = /^(\d)+$/gi;
                if(!reg.test($(this).val())) {
                    alert('输入数量有问题');
                    $(this).val('');
                    return;
                }
                obj = $(this).parent().parent();
                warehouse =  $('#out_warehouse_id').val();
                position = obj.find('.warehouse_position_id').val();
                sku = obj.find('.sku').val();
                if($(this).val() > parseInt(obj.find('.access_quantity').val())) {
                    alert('超出可用数量');
                    $(this).val('');
                    obj.find('.amount').val('');
                    return;
                }
                $.ajax({
                    url:"{{ route('allotsku') }}",
                    data:{warehouse:warehouse, item_id:sku},
                    dataType:'json',
                    'type':'get',
                    success:function(result){
                        if(result != 'none') {
                            obj.find('.amount').val((result[2]*obj.find('.quantity').val()).toFixed('3'));
                        }
                    }
                });
            }
        });

        $('#allotment_time').cxCalendar();
    });
</script>