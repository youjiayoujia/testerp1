@extends('common.form')
@section('formAction') {{ route('orderBlacklist.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">收货人姓名</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="收货人姓名" name='name' value="{{ old('name') ?  old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="email" class="control-label">邮箱</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') ?  old('email') : $model->email }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="zipcode" class="control-label">收货人邮编</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="zipcode" placeholder="收货人邮编" name='zipcode' value="{{ old('zipcode') ?  old('zipcode') : $model->zipcode }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="whitelist">纳入白名单</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <div class="radio">
                <label>
                    <input type="radio" name="whitelist" value="1" {{old('whitelist') ? (old('whitelist') == "1" ? 'checked' : '') : ($model->whitelist == "1" ? 'checked' : '')}}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="whitelist" value="0" {{old('whitelist') ? (old('whitelist') == "0" ? 'checked' : '') : ($model->whitelist == "0" ? 'checked' : '')}}>否
                </label>
            </div>
        </div>
    </div>
@stop