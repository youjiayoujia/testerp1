@extends('common.form')
@section('formAction') {{ route('logisticsZone.store') }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <div class="form-group col-lg-4">
        <label for="name" class="control-label">物流分区</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="name" placeholder="命名建议:shipping+数字(1区取1,2区取2,其他区取99)"
               name='name' value="{{ old('name') }}">
    </div>
    <div class="form-group col-lg-4">
        <label for="logistics_id">物流方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="logistics_id" class="form-control" id="logistics_id">
            @foreach($logistics as $logisticses)
                <option value="{{ $logisticses->id }}" {{ $logisticses->id == old('$logisticses->logistics->id') ? 'selected' : '' }}>
                    {{ $logisticses->logistics_type }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-4">
        <label for="countries" class="control-label">国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" name="countries" value="GLOBAL" {{old('countries') ? (old('countries') == 'GLOBAL' ? 'checked' : '') : ''}}>GLOBAL
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" name="countries" value="US" {{old('countries') ? (old('countries') == 'US' ? 'checked' : '') : ''}}>US
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" name="countries" value="UK" {{old('countries') ? (old('countries') == 'UK' ? 'checked' : '') : ''}}>UK
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" name="countries" value="CN" {{old('countries') ? (old('countries') == 'CN' ? 'checked' : '') : ''}}>CN
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" name="countries" value="DE" {{old('countries') ? (old('countries') == 'DE' ? 'checked' : '') : ''}}>DE
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" name="countries" value="FR" {{old('countries') ? (old('countries') == 'FR' ? 'checked' : '') : ''}}>FR
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" name="countries" value="JP" {{old('countries') ? (old('countries') == 'JP' ? 'checked' : '') : ''}}>JP
            </label>
        </div>
    </div>
@stop