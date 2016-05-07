@extends('common.form')
@section('formAction')  {{ route('purchaseAbnormal.update', ['id' => $model->id]) }}  @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <input type="hidden" name="update_userid" value="2"/>
    <div class="row">
        <div class="form-group col-lg-4">
            <label for="warehouse">仓库:</label>
            {{$model->warehouse->name}}
        </div>
        <div class="form-group col-lg-4">
            <label for="sku_id">sku:</label>
            {{$model->sku}}
        </div>
        <div class="form-group col-lg-4">
            <label for="type">异常种类：</label>
            @foreach(config('purchase.purchaseItem.active') as $k=>$active)
                @if($model->active == $k)
                    <td>{{ $active }}</td>
                @endif
            @endforeach
        </div>
    </div>
@stop
 
 
 
 