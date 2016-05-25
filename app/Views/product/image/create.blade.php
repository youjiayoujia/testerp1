@extends('common.form')
@section('formAction') {{ route('productImage.store') }} @stop
@section('formBody')
    
  <div class="container">
   <form enctype="multipart/form-data" method="POST" action="upload-handler" class="form">
    <div class="alert alert-info">
     <strong>请在这里上传产品公共图</strong>
     <ul>
      <li>“实拍图”和“链接图”请根据实际情况选择；</li>
      <li>图片类型包括Logo，Size，色卡，请在下方选择；</li>
     </ul>
    </div>
    <div class="panel panel-primary parent-sku-panel">
     <div class="panel-heading">
      {{$model}}公共图片
     </div>
     <div class="panel-body">
      <div class="panel panel-default image-panel">
       <div class="panel-body">
        <div class="row">
         <div class="col-md-6">
          <div class="input-group">
           <div class="input-group-addon">
            <span class="glyphicon glyphicon-tag"></span>&nbsp;SKU:
           </div>
           <input type="text" value="{{$model}}" disabled="" class="form-control" id="model" />

          </div>
         </div>
        </div>
       </div>
      </div>
      <ul class="dowebok">
        <li><input type="radio" name="is_link" data-labelauty="实拍图" value="1"></li>
        <li><input type="radio" name="is_link" data-labelauty="链接图" value="2"></li>
      </ul>
      <ul class="dowebok">
        <li><input type="radio" name="image_type" data-labelauty="普通图" value="3"></li>
        <li><input type="radio" name="image_type" data-labelauty="色卡" value="4"></li>
        <li><input type="radio" name="image_type" data-labelauty="Logo" value="5"></li>
        <li><input type="radio" name="image_type" data-labelauty="Size" value="6"></li>
      </ul>
      <div class="form-group">
        <input id="file-1" class="file" type="file" multiple data-preview-file-type="any" data-sku="ssdf" input-name="dlo">
      </div>
    </div>

    <div class="btn-group tags">
        <button class="btn btn-xs photoBtn btn-success active" data-tag="photo"
        title="实拍图">
            <span class="glyphicon glyphicon-picture">
            </span>
        </button>
        <button class="btn btn-xs linkBtn btn-default" data-tag="link" title="链接图">
            <span class="glyphicon glyphicon-link">
            </span>
        </button>
        <button class="btn btn-xs shapeBtn btn-default" data-tag="shape" title="外观图">
            <span class="glyphicon glyphicon-tree-deciduous">
            </span>
        </button>
        <button class="btn btn-xs frontBtn btn-default" data-tag="front" title="正面图">
            <span class="glyphicon glyphicon-home">
            </span>
        </button>
    </div>

    @section('formButton')
    @stop
@stop

@section('pageJs')
    <link href="{{ asset('css/fileinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-labelauty.css') }}" rel="stylesheet">
    <script src="{{ asset('js/fileinput.js') }}"></script>
    <script src="{{ asset('js/jquery-labelauty.js') }}"></script>
    <script type="text/javascript">

        $("#file-1").fileinput({
            uploadUrl: "{{route('productImage.store')}}",
            uploadExtraData: function() {
            return {
                is_link:$('input[name="is_link"]:checked').val(),
                image_type: $('input[name="image_type"]:checked').val(),
                model:$("#model").val(),
            };
        }
            //key: 100,
            //data:{"id":"12234"},
            //uploadExtraData:{id:'kv-1'},
            //uploadExtraData: function () {
            //    return {
            //extradata: { product_ID: $('#Product_ID').val(), type: "marketing_materials_EN"}
            //    };
            //}
        });
      
        $(':input').labelauty();
        

    </script>
@stop
 
<style>
ul { list-style-type: none;}
li { display: inline-block;}
li { margin: 10px 0;}
input.labelauty + label { font: 12px "Microsoft Yahei";}
</style>

 