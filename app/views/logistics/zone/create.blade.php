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
                <input type="checkbox" id="countries" value="GLOBAL">GLOBAL
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" value="US">US
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" value="UK">UK
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" value="CN">CN
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" value="DE">DE
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" value="FR">FR
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" id="countries" value="JP">JP
            </label>
        </div>
    </div>
@stop