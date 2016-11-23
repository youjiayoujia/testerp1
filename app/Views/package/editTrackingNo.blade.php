@extends('common.form')
@section('formAction') {{ route('package.editTrackStore', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type='hidden' name='hideUrl' value="{{$hideUrl}}">
    <div class="panel panel-default">
        <div class="panel-heading">包裹基础信息</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <label>订单号</label>
                    <input type='text' class='form-control' value="{{ $model->order ? $model->order->ordernum : '无订单号' }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>渠道</label>
                    <input type='text' class='form-control' value="{{ $model->channel ? $model->channel->name : '无渠道' }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>收货人</label>
                    <input type='text' class='form-control' value="{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>电话</label>
                    <input type='text' class='form-control' value="{{ $model->shipping_phone }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>发货邮编</label>
                    <input type='text' class='form-control' value="{{ $model->shipping_zipcode }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>城市</label>
                    <input type='text' class='form-control' value="{{ $model->shipping_city }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>区</label>
                    <input type='text' class='form-control' value="{{ $model->shipping_state }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>国家</label>
                    <input type='text' class='form-control' value="{{ $model->shipping_country }}" readonly>
                </div>
                <div class="col-lg-2">
                    <label>物流</label>
                    <input type='text' class='form-control' value="{{ $model->logistics ? $model->logistics->name : '暂无物流方式' }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <label>备注</label>
                <textarea class='form-control' readonly>{{ $model->remark }}</textarea>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">包裹Item信息</div>
        <div class="panel-body">
            <table class='table table-bordered'>
                <thead>
                    <th>sku</th>
                    <th>库位</th>
                    <th>数量</th>
                </thead>
                <tbody>
                    @foreach($model->items as $packageItem)
                    <tr>
                        <td>{{ $packageItem->item ? $packageItem->item->sku : ''}}</td>
                        <td>{{ $packageItem->warehousePosition ? $packageItem->warehousePosition->name : ''}}</td>
                        <td>{{ $packageItem->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">修改追踪号</div>
        <div class="panel-body">
            <div class="col-lg-2">
                <label>修改追踪号</label>
                <input type='text' class='form-control' name='tracking_no' value="{{ $model->tracking_no }}">
            </div>
            <div class="col-lg-2">
                <label>发货地址</label>
                <input type='text' class='form-control' name='shipping_address' value="{{ $model->shipping_address }}">
            </div>
        </div>
    </div>
@stop