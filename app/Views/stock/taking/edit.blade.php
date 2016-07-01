@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('stockTaking.update', ['id' => $model->id] ) }} @stop
@section('formBody')
    <input type='hidden' name='_method' value='PUT'>
    <div class='row'>
        <div class="form-group col-lg-1">
            <label for="ID" class='control-label'>ID</label>
        </div>
        <div class="form-group col-lg-2">
            <label for="sku" class='control-label'>sku</label>
        </div>
        <div class="form-group col-lg-2">
            <label for="仓库" class='control-label'>仓库</label>
        </div>
        <div class="form-group col-lg-2">
            <label for="库位" class='control-label'>库位</label>
        </div>
        <div class="form-group col-lg-1">
            <label for="可用数量" class='control-label'>可用数量</label>
        </div>
        <div class="form-group col-lg-1">
            <label for="hold数量" class='control-label'>hold数量</label>
        </div>
        <div class="form-group col-lg-1">
            <label for="总数量" class='control-label'>总数量</label>
        </div>
        <div class="form-group col-lg-2">
            <label for="实盘数量" class='control-label'>实盘数量</label>
            <a href='javascript:' class='btn btn-info fill'>填入默认值</a>
        </div>
    </div>
    @foreach($takingForms as $key => $takingForm)
    @if($takingForm->quantity && ($takingForm->stock ? ($takingForm->stock->all_quantity - $takingForm->quantity > $takingForm->stock->available_quantity) : ''))
        <div class='row tr_bgcolor'>
    @else
        <div class='row'>
    @endif
            <div class="form-group col-lg-1">
                <input type='text' name='arr[id][{{$key}}]' class='form-control' value="{{ $takingForm->id }}"readonly>
            </div>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[sku][{{$key}}]' class='form-control' value="{{ $takingForm->stock ? $takingForm->stock->item ? $takingForm->stock->item->sku : '' : '' }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[warehouse_id][{{$key}}]' class='form-control' value="{{ $takingForm->stock ? $takingForm->stock->warehouse ? $takingForm->stock->warehouse->name : '' : '' }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[warehouse_position_id][{{$key}}]' class='form-control' value="{{ $takingForm->stock ? $takingForm->stock->position ? $takingForm->stock->position->name : '' : '' }}" readonly>
            </div>
            <div class="form-group col-lg-1">
                <input type='text' name='arr[available_quantity][{{$key}}]' class='form-control' value="{{ $takingForm->stock ? $takingForm->stock->available_quantity : '' }}" readonly>
            </div>
            <div class="form-group col-lg-1">
                <input type='text' name='arr[hold_quantity][{{$key}}]' class='form-control' value="{{ $takingForm->stock ? $takingForm->stock->hold_quantity : '' }}" readonly>
            </div>
            <div class="form-group col-lg-1">
                <input type='text' name='arr[all_quantity][{{$key}}]' class='form-control all_quantity' value="{{ $takingForm->stock ? $takingForm->stock->all_quantity : '' }}" readonly>
            </div>
            <div class="form-group col-lg-2">
                <input type='text' name='arr[quantity][{{$key}}]' class='form-control quantity' placeholder='实盘数量' value={{$takingForm->quantity !== NULL ? $takingForm->quantity : ''}}>
            </div>
        </div>
    @endforeach
@stop
<script type='text/javascript'>
    $(document).ready(function(){
        $('.fill').click(function(){
            $.each($('.all_quantity'), function(){
                $(this).parent().next().find(".quantity").val($(this).val());
            });
        });
    });
</script>