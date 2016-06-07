@extends('common.form')
@section('formAction') {{ route('productMultiUpdate') }} @stop
@section('formBody')

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
        @foreach($languages as $name=>$language) 
            
                <label for="{{$language}}" >{{$channel->name}}{{$language}}:</label>
                <div class="row">
                    <?php 
                        $temp=$name."_name";
                        $multiOption = $model->productMultiOption->where("channel_id",$channel->id)->first();
                    ?>
                    <div class="form-group col-lg-4">
                        <input type='text' class="form-control" id="{{$name}}_name" placeholder="标题" name='info[{{$channel->id}}][{{$name}}][{{$name}}_name]' value="{{ old($name.'_name')?old($name.'_name'):$multiOption->$temp }}">
                    </div>
                    <?php $temp=$name."_description" ?>
                    <div class="form-group  col-lg-4">  
                        <textarea cols="50" rows="10" id="{{$name}}_description" name="info[{{$channel->id}}][{{$name}}][{{$name}}_description]">{{$multiOption->$temp}}</textarea>
                    </div>
                    <?php $temp=$name."_keywords" ?>
                    <div class="form-group  col-lg-4">    
                        <input type='text' class="form-control" id="{{$name}}_keywords" placeholder="关键词" name='info[{{$channel->id}}][{{$name}}][{{$name}}_keywords]' value="{{ old($name.'_keywords')?old($name.'_keywords'):$multiOption->$temp }}">
                    </div>
                </div> 
            
        @endforeach
        </div>
    @endforeach
</div>

<!--<div class="tab-content">
    <div class="tab-pane active" id="taobao">
        <label for="德语">
            taobao德语:
        </label>
        <div class="row">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="de_name" placeholder="标题"
                name='info[1][de][de_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="de_description" placeholder="描述"
                name='info[1][de][de_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="de_keywords" placeholder="关键词"
                name='info[1][de][de_keywords]' value="">
            </div>
        </div>
        <label for="意大利语">
            taobao意大利语:
        </label>
        <div class="row">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="it_name" placeholder="标题"
                name='info[1][it][it_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="it_description" placeholder="描述"
                name='info[1][it][it_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="it_keywords" placeholder="关键词"
                name='info[1][it][it_keywords]' value="">
            </div>
        </div>
        <label for="法语">
            taobao法语:
        </label>
        <div class="row">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="fr_name" placeholder="标题"
                name='info[1][fr][fr_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="fr_description" placeholder="描述"
                name='info[1][fr][fr_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="fr_keywords" placeholder="关键词"
                name='info[1][fr][fr_keywords]' value="">
            </div>
        </div>
        <label for="中文">
            taobao中文:
        </label>
        <div class="row">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="zh_name" placeholder="标题"
                name='info[1][zh][zh_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="zh_description" placeholder="描述"
                name='info[1][zh][zh_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="zh_keywords" placeholder="关键词"
                name='info[1][zh][zh_keywords]' value="">
            </div>
        </div>
    </div>

    <div class="tab-pane" id="Amazon">
        <label for="德语">
            Amazon德语:
        </label>
        <div class="row tab-pane" id="Amazon">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="de_name" placeholder="标题"
                name='info[2][de][de_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="de_description" placeholder="描述"
                name='info[2][de][de_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="de_keywords" placeholder="关键词"
                name='info[2][de][de_keywords]' value="">
            </div>
        </div>
        <label for="意大利语">
            Amazon意大利语:
        </label>
        <div class="row tab-pane" id="Amazon">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="it_name" placeholder="标题"
                name='info[2][it][it_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="it_description" placeholder="描述"
                name='info[2][it][it_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="it_keywords" placeholder="关键词"
                name='info[2][it][it_keywords]' value="">
            </div>
        </div>
        <label for="法语">
            Amazon法语:
        </label>
        <div class="row tab-pane" id="Amazon">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="fr_name" placeholder="标题"
                name='info[2][fr][fr_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="fr_description" placeholder="描述"
                name='info[2][fr][fr_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="fr_keywords" placeholder="关键词"
                name='info[2][fr][fr_keywords]' value="">
            </div>
        </div>
        <label for="中文">
            Amazon中文:
        </label>
        <div class="row tab-pane" id="Amazon">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="zh_name" placeholder="标题"
                name='info[2][zh][zh_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="zh_description" placeholder="描述"
                name='info[2][zh][zh_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="zh_keywords" placeholder="关键词"
                name='info[2][zh][zh_keywords]' value="">
            </div>
        </div>
    </div>
    <div class="tab-pane" id="eBay">
        <label for="德语">
            eBay德语:
        </label>
        <div class="row">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="de_name" placeholder="标题"
                name='info[3][de][de_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="de_description" placeholder="描述"
                name='info[3][de][de_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="de_keywords" placeholder="关键词"
                name='info[3][de][de_keywords]' value="">
            </div>
        </div>
        <label for="意大利语">
            eBay意大利语:
        </label>
        <div class="row">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="it_name" placeholder="标题"
                name='info[3][it][it_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="it_description" placeholder="描述"
                name='info[3][it][it_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="it_keywords" placeholder="关键词"
                name='info[3][it][it_keywords]' value="">
            </div>
        </div>
        <label for="法语">
            eBay法语:
        </label>
        <div class="row">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="fr_name" placeholder="标题"
                name='info[3][fr][fr_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="fr_description" placeholder="描述"
                name='info[3][fr][fr_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="fr_keywords" placeholder="关键词"
                name='info[3][fr][fr_keywords]' value="">
            </div>
        </div>
        <label for="中文">
            eBay中文:
        </label>
        <div class="row">
            <div class="form-group col-lg-4">
                <input type='text' class="form-control" id="zh_name" placeholder="标题"
                name='info[3][zh][zh_name]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="zh_description" placeholder="描述"
                name='info[3][zh][zh_description]' value="">
            </div>
            <div class="form-group  col-lg-4">
                <input type='text' class="form-control" id="zh_keywords" placeholder="关键词"
                name='info[3][zh][zh_keywords]' value="">
            </div>
        </div>
    </div>
</div>-->
<input type="hidden" value="728" name="product_id">

@stop
@section('pageJs')
<script type="text/javascript">
    $(function () { 
        $('#myTab a:first').tab('show');//初始化显示哪个tab 
      
        $('#myTab a').click(function (e) { 
          e.preventDefault();//阻止a链接的跳转行为 
          $(this).tab('show');//显示当前选中的链接及关联的content 
        }) 
      })
</script>
@stop