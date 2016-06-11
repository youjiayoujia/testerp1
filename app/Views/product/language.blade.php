@extends('common.form')
@section('formAction') {{ route('productMultiUpdate') }} @stop
@section('formBody')
<script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
<script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
<script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
<link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">
<ul class="nav nav-tabs" id="myTab">
    @foreach($channels as $channel)
        <li>
            <a href="#{{$channel->name}}">
                {{$channel->name}}
            </a>
        </li>
    @endforeach
</ul>
<br>

<div class="tab-content">
    @foreach($channels as $key=>$channel)
    <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="{{$channel->name}}">      
        <label for="" >渠道：{{$channel->name}}</label>
        <div>请选择编辑的语言</div>
            <ul class="dowebok">
                @foreach($languages as $la_name=>$language)
                    <li><input type="radio" name="info[{{$channel->id}}][language]" data-labelauty="{{$language}}" value="{{$la_name}}" {{$la_name=='de'?'checked':''}}></li>
                @endforeach    
            </ul>
        <div class="row">
            <?php 
                //$temp=$de_name."_name";
                //$multiOption = $model->productMultiOption->where("channel_id",$channel->id)->first();
            ?>
            <div class="form-group col-lg-12">
                <input type='text' class="form-control" id="" placeholder="标题" name='info[{{$channel->id}}][name]' value="">
            </div>
            <?php //$temp=$name."_description" ?>
            <!--<div class="form-group  col-lg-12">  
                <textarea cols="50" rows="10" id="" name="info[{{$channel->id}}][description]"></textarea>
            </div>-->
            <?php //$temp=$name."_keywords" ?>
            <div class="form-group  col-lg-12">    
                <input type='text' class="form-control" id="" placeholder="关键词" name='info[{{$channel->id}}][keywords]' value="">
            </div>
        </div> 
        <div class="row">

            <div class="col-lg-12" id="templateContent">
                <label for="" >描述：</label>
                <div class="form-group">
                    <textarea class="form-control" id="editor" rows="16" placeholder="标题" name="info[{{$channel->id}}][description]" style="width:100%;height:400px;">{{ old('content') }}</textarea>
                </div>
            </div>
        </div>
        <script type="text/javascript" charset="utf-8"> var um = UM.getEditor('editor'); </script>
    </div>
    @endforeach
</div>



<input type="hidden" value="{{$id}}" name="product_id">

@stop
@section('pageJs')

<script src="{{ asset('js/jquery-labelauty.js') }}"></script>
<link href="{{ asset('css/jquery-labelauty.css') }}" rel="stylesheet">
<script type="text/javascript">
    $(':input').labelauty();
    $(function () { 
        $('#myTab a:first').tab('show');//初始化显示哪个tab 
      
        $('#myTab a').click(function (e) { 
          e.preventDefault();//阻止a链接的跳转行为 
          $(this).tab('show');//显示当前选中的链接及关联的content 
        }) 
      })
</script>
@stop
<style>
.dowebok ul { list-style-type: none;}
.dowebok li { display: inline-block;}
.dowebok li { margin: 10px 0;}
input.labelauty + label { font: 12px "Microsoft Yahei";}
</style>