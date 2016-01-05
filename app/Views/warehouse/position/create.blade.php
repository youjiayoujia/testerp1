@extends('common.form')
@section('formAction') {{ route('warehousePosition.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="库位名字" name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group">
        <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name='warehouses_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ $warehouse->id == old('$warehouse->warehouse->id') ? 'selected' : '' }}>{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="remark">备注信息</label>
        <input type='text' class="form-control" id="remark" placeholder="备注信息" name='remark' value="{{ old('remark') }}">
    </div>
    <div class="form-group">
        <label for="size">库位大小</label>
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='小' {{ old('size') ? (old('size') == '小' ? 'checked' :  '') : '' }}>小
            </label>
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='中' {{ old('size') ? (old('size') == '中' ? 'checked' :  '') : 'checked' }}>中
            </label>
        </div> 
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='大' {{ old('size') ? (old('size') == '大' ? 'checked' :  '') : '' }}>大
            </label>
        </div>       
    </div>
    <div class="form-group">
        <label for="is_available">库位是否启用</label>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='Y' {{ old('is_available') ? (old('is_available') == 'Y' ? 'checked' :  '') : '' }}>启用
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='N' {{ old('is_available') ? (old('is_available') == 'N' ? 'checked' :  '') : 'checked' }}>不启用
            </label>
        </div>    
    </div>
@stop