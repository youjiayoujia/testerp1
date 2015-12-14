@extends('common.form')
@section('title') 编辑库位 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('warehousePosition.index') }}">库位</a></li>
        <li class="active"><strong>编辑库位</strong></li>
    </ol>
@stop
@section('formTitle') 编辑供货商 @stop
@section('formAction') {{ route('warehousePosition.update', ['id' => $warehousePosition->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group">
        <label for="name" class='control-label'>名称</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="name" placeholder="库位名字" name='name' value="{{ old('name') ? old('name') : $warehousePosition->name }}">
    </div>
    <div class="form-group">
        <label for="warehouses_id">仓库</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name='warehouses_id' class='form-control'>
            @foreach($warehousePositions as $position)
                <option value="{{ $position->id }}" {{ $position->id == $warehousePosition->warehouses_id ? 'selected' : '' }}>
                    {{ $position->name }}
                </option>
            @endforeach    
        </select>
    </div>
    <div class="form-group">
        <label for="remark">备注信息</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input type='text' class="form-control" id="remark" placeholder="备注信息" name='remark' value="{{ old('remark') ? old('remark') : $warehousePosition->remark }}">
    </div>
    <div class="form-group">
        <label for="size">库位大小</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='小' {{ $warehousePosition->size == '小' ? 'checked' : '' }}>小
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='中' {{ $warehousePosition->size == '中' ? 'checked' : '' }}>中
            </label>
        </div> 
        <div class='radio'>
            <label>
                <input type='radio' name='size' value='大' {{ $warehousePosition->size == '大' ? 'checked' : '' }}>大
            </label>
        </div>       
    </div>
    <div class="form-group">
        <label for="is_available">库位是否启用</label> <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='Y' {{ $warehousePosition->is_available == 'Y' ? 'checked' : '' }}>启用
            </label>   
        </div>
        <div class='radio'>
            <label>
                <input type='radio' name='is_available' value='N' {{ $warehousePosition->is_available == 'N' ? 'checked' : '' }}>不启用
            </label>
        </div>    
    </div>
@stop