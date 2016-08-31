<div class="form-group col-lg-2">
    <label>箱号</label>
    <input type='text' class="form-control" value="{{ $model->boxNum }}">
    <input type='hidden' class='boxId' value="{{ $model->id }}">
</div>
<div class="form-group col-lg-2">
    <label>sku数量</label>
    <input type='text' class="form-control box_quantity" value="0">
</div>
<div class="form-group col-lg-2">
    <label>重量</label>
    <input type='text' class="form-control box_weight" value=0>
</div>