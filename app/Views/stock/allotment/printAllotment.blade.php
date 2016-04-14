@extends('common.form')
@section('formBody')
    <div class='row'>
        <div class='form-group col-lg-2'>
            <label>ID:</label>
            <input type='text' class='form-control' value="{{ $model->id }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>调拨单号:</label>
            <input type='text' class='form-control' value="{{ $model->allotment_id }}">
        </div>
        <div class='form-group col-lg-2'>
            <label>审核人</label>
            <input type='text' class='form-control' value={{ $model->checkByName ? $model->checkByName->name : '' }}>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col-lg-12'>
            <label>备注:</label>
            <textarea name='remark' class='form-control'>{{ $model->remark }}</textarea>
        </div>
    </div>
    <table class='table table-bordered'>
        <thead>
            <th>sku</th>
            <th>库位</th>
            <th>数量</th>
            <th>调拨单号</th>
        </thead>
        <tbody>
            @foreach($allotmentforms as $key => $allotmentform)
            <tr>
                <td>{{ $allotmentform->item ? $allotmentform->item->sku : '' }}</td>
                <td>{{ $allotmentform->position ? $allotmentform->position->name : '' }}</td>
                <td>{{ $allotmentform->quantity }}</td>
                <td>{{ $model->allotment_id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop
@section('formButton')
    <button type="button" class="btn btn-success">打印</button>
@stop