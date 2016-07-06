@extends('common.form')
@section('formAction') {{ route('logisticsChannelName.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-2">
            <label for="channel" class="control-label">渠道</label>
            <select name='channel_id' class='form-control'>
            @foreach($channels as $channel)
                <option value="{{ $channel->id }}" {{ $channel->id == $model->channel_id ? 'selected' : ''}}>{{ $channel->name }}</option>
            @endforeach
            </select>
        </div>
        <div class='form-group col-lg-2'>
            <label for="name" class="control-label">名称</label>
            <input type='text' name='name' placeholder='名称' class='form-control' value="{{ old('name') ? old('name') : $model->name }}">
        </div>
    </div>
@stop