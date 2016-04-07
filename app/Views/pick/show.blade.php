@extends('common.detail')
@section('detailBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID</label>
            <input type='text' class='form-control' value={{ $model->picknum }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>类型</label>
            <input type='text' class='form-control' value={{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>状态</label>
            <input type='text' class='form-control' value={{ $model->status_name }}>
        </div>
    </div>
    <table class='table table-bordered table-condensed'>
        <thead>
            <td class='col-lg-4'>package ID</td>
            <td class='col-lg-4'>sku</td>
            <td class='col-lg-4'>数量</td>
        </thead>
        <tbody>
        @foreach($packages as $package)
            <table class='table table-bordered table-condensed'>
            @foreach($package->items as $key => $packageitem)
                <tr>
                    @if($key == '0')
                    <td rowspan="{{$package->items()->count()}}" class='package_id col-lg-4'>{{ $package->id }}</td>
                    @endif
                    <td class='sku col-lg-4'>{{ $packageitem->item ? $packageitem->item->sku : '' }}</td>
                    <td class='quantity col-lg-4'>{{ $packageitem->quantity}}</td>
                </tr>
            @endforeach
            </table>
        @endforeach
        </tbody>
    </table>
@stop