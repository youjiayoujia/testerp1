<div>sku：{{$model->sku}}</div>
<div>中文名：{{$model->name}}</div>
<div>日期：{{$model->created_at}}</div>
<div>库位号：{{$model->warehousePosition->name}}</div>
<?php echo Tool::barcodePrint($model->id) ?>