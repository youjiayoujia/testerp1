@extends('common.form')

<link rel="stylesheet" href="{{ asset('css/jquery.cxcalendar.css') }}">
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
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
        <label for="species">种类</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="species" class="form-control" id="species">
            @foreach($logistics as $logisticses)
                <option value="{{ $logisticses->id }}" {{ $logisticses->id == old('$logisticses->logistics->id') ? 'selected' : '' }}>
                    {{ $logisticses->species }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" value="1">1
        </label>
    </div>
    <div class="form-group col-lg-4">
        <label for="country_id" class="control-label">国家</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <select name="country_id" class="form-control" id="country_id" onclick="select();">
            @foreach($country as $countries)
                <option class="checkbox" value="{{ $countries->id }}" {{ $countries->id == old('$countries->country->id') ? 'selected' : '' }}>
                    {{ $countries->name }}
                </option>
            @endforeach
        </select>
    </div>
@stop

<script type="text/javascript">
    function select() {

        alert(document.getElementById('name').value());
        alert(null);

    }

    $('country_id').multiselect({

    });

    $.ajax({
        url : "{{ route('zone') }}",
        data : {id:4},
        dataType : 'json',
        type : 'get',
        success : function(result) {
            alert(result);
        }
    });
</script>
