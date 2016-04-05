@extends('common.form')
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID:</label>
            <input type='text' class='form-control' value="{{ $model->picknum }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>类型:</label>
            <input type='text' class='form-control' value={{ $model->type == 'SINGLE' ? '单单' : ($model->type == 'SINGLEMULTI' ? '单多' : '多多') }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>状态:</label>
            <input type='text' class='form-control' value={{ $model->status_name }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>物流:</label>
            <input type='text' class='form-control' value={{ $model->logistic ? $model->logistic->logistics_type : '混合物流' }}>
        </div>
        <div class='form-group col-lg-2'>
            <label>拣货人:</label>
            <input type='text' class='form-control' value={{ $model->pickByName ? $model->pickByName->name : '' }}>
        </div>
    </div>
    <table class='table table-bordered'>
        <thead>
            <th>sku</th>
            <th>库位</th>
            <th>数量</th>
            <th>拣货单id</th>
        </thead>
        <tbody>
            @foreach($picklistitems as $key => $picklistitem)
            <tr>
                <td>{{ $picklistitem->items ? $picklistitem->items->sku : '' }}</td>
                <td>{{ $picklistitem->position ? $picklistitem->position->name : '' }}</td>
                <td>{{ $picklistitem->quantity }}</td>
                <td>{{ $model->picknum }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop
@section('formButton')
    <button type="button" class="btn btn-success">打印</button>
@stop