@extends('common.detail')
@section('detailBody')
<div class="panel panel-default">
    <div class="panel-heading"><strong>模板名</strong>: {{ $model->name }}</div>
    <div class="panel-body">
        <div class="row">
                <div class='form-group col-lg-2'>
                    <label>字段</label>
                </div>
                <div class='form-group col-lg-2'>
                    <label>排序字母</label>
                </div>
        </div>
        @foreach($exportPackageItems as $key => $exportPackageItem)
            <div class="row">
                <div class='form-group col-lg-2'>
                    <input type='text' class="form-control col-lg-2" value="{{ $arr[$exportPackageItem->name] }}">
                </div>
                <div class='form-group col-lg-2'>
                    <input type='text' class="form-control col-lg-2" value="{{ $exportPackageItem->level }}">
                </div>
            </div>
        @endforeach
    </div>
</div>
@stop