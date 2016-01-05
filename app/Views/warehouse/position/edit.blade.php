@extends('common.form')
@section('title') 编辑库位 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehousePosition.index') }}">库位</a></li>
        <li class="active"><strong>编辑库位</strong></li>
    </ol>
@stop
@section('formTitle') 编辑库位 @stop
@section('formAction') {{ route('warehousePosition.update', ['id' => $position->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="库位名字" name='name' value="{{ old('name') ? old('name') : $position->name }}">
    </div>
    <div class="form-group">
        <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name='warehouses_id' class='form-control'>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ old('warehouses_id') ? (old('warehouses_id') == $wareshouse->id ? 'selected' : '') : ($warehouse->id  == $position->warehouses_id ? 'selected' : '') }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach    
        </select>
    </div>
    <div class="form-group">
        <label for="remark">备注信息</label>
        <input type='text' class="form-control" id="remark" placeholder="备注信息" name='remark' value="{{ old('remark') ? old('remark') : $position->remark }}">
    </div>
    <div class="form-group">
        <label for="size">库位大小</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='小' {{ old('size') ? old('size') == '小' ? 'checked' : '' : $position->size == '小' ? 'checked' : '' }}>小
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='中' {{ old('size') ? old('size') == '中' ? 'checked' : '' : $position->size == '中' ? 'checked' : '' }}>中
            </label>
        </div> 
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='大' {{ old('size') ? old('size') == '大' ? 'checked' : '' : $position->size == '大' ? 'checked' : '' }}>大
            </label>
        </div>       
    </div>
    <div class="form-group">
        <label for="is_available">库位是否启用</label>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='Y' {{ old('is_available') ? old('is_available') == 'Y' ? 'checked' : '' : $position->is_available == 'Y' ? 'checked' : '' }}>启用
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='N' {{ old('is_available') ? old('is_available') == 'N' ? 'checked' : '' : $position->is_available == 'N' ? 'checked' : '' }}>不启用
            </label>
        </div>    
    </div>
@stop