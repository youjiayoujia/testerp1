<div class="panel panel-default">
    <div class="panel-heading">
        订单产品
    </div>
    <div class="panel-body" id="itemDiv">
        <div class='row'>
            <div class="form-group col-lg-1"></div>
            <div class="form-group col-lg-4">
                <label for="sku" class='control-label'>sku</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
            <div class="form-group col-lg-1">
                <label for="qty" class='control-label'>数量</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
            <div class="form-group col-lg-6">
                <label for="remark" class='control-label'>备注</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
            </div>
        </div>
        @foreach($order->items as $item)
            <div class='row'>
                <div class="form-group col-lg-1">
                    <input type="checkbox" checked/>
                </div>
                <div class="form-group col-lg-4">
                    {{ $item->sku }}
                </div>
                <div class="form-group col-lg-1">
                    <input type="text" class="form-control" value="{{ $item->qty }}"/>
                </div>
                <div class="form-group col-lg-6">
                    <input type="text" class="form-control"/>
                </div>
            </div>
        @endforeach
    </div>
</div>