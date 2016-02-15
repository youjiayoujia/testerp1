@extends('common.form')
@section('formAction') {{ route('catalog.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="name">分类名称</label>
        <input class="form-control" id="name" placeholder="名称" name='name' value="{{old('name')}}">
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Set属性</div>
        <div class="panel-body setfirst">
            <button type="button" class="btn btn-primary btn-lg btn-block" id='setadd'>添加set</button>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Attribute属性</i></div>
        <div class="panel-body attributefirst">
            <button type="button" class="btn btn-primary btn-lg btn-block" id='attradd'>添加attribute</button>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Feature属性</div>
        <div class="panel-body featurefirst">
            <button type="button" class="btn btn-primary btn-lg btn-block" id='featureadd'>添加feature</button>
        </div>
    </div>
    <input type='hidden' value='0' id='setnum' name="setnum" >
    <input type='hidden' value='0' id='attrnum' name="attrnum" >
    <input type='hidden' value='0' id='featurenum' name="featurenum" >
@stop

@section('pageJs')
    <script type="text/javascript">
        {{-- 删除属性列  --}}
        $(document).on('click','.delete-row',function(){
            var o = $(this).parent();
            o.remove();    
        });

        {{-- 删除属性行  --}}
        $(document).on('click','.delete-column',function(){
            var o = $(this).parent();
            o.remove();            
        });

        {{-- feature类型选择  --}}
        $(document).on('change','.featype',function(){
            var num = $(this).attr('name');
            num = num.substr(9,1);
            var type = $(this).val(); 
            if(type==1){
                $(".fhide_"+num).css("display","none");
                $(".dhide_"+num).css("display","none");
                $(".fhides_"+num).remove();
            }else{
                $(".fhide_"+num).css("display","inline");
            }      
        });

        {{-- 添加set属性值  --}}
        $(document).on('click','.setsvalues',function(){
            var aa = $(this).prev().prev().find('input').attr('name');
            var num = aa.substr(5,1);
            num = parseInt(num);
            $(this).next().css("display","inline");
            $(this).prev().after("<div class='form-group ajaxinput'><input type='text' class='form-control'  placeholder='属性值' name='sets["+num+"][value][name][][name]'><button type='button' class='btn btn-outline btn-danger delete-column ajaxinput'><i class='glyphicon glyphicon-remove'></i></div>");            
        });

        {{-- 添加set属性行  --}}
        $(document).on('click','#setadd',function(){
            var aa = $("input[name^='sets[']:last").attr('name');
            if(aa==undefined){
                $(".setfirst").html("<div class='form-group form-inline sets' id='setkey_0'> 属性名：<div class='form-group'><input class='form-control'  placeholder='属性名'  name='sets[0][name]' ></div> 属性值：<div class='form-group' title='cannotremove'><input type='text' class='form-control'  placeholder='属性值' name='sets[0][value][name][][name]' ></div><button type='button' class='btn btn-primary setsvalues ajaxinput'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div><button type='button' class='btn btn-primary btn-lg btn-block' id='setadd'>添加set</button></div>");
                return;
            }
            var num = aa.substr(5,1);
            num = parseInt(num);
            num = num+1;
            $("#setnum").val(num);
            $(".sets").last().after("<div class='form-group form-inline sets '>属性名：<div class='form-group'><input class='form-control'  placeholder='属性名'  name='sets["+num+"][name]' ></div> 属性值：<div class='form-group'><input type='text' class='form-control'  placeholder='属性值' name='sets["+num+"][value][name][][name]'></div><button type='button' class='btn btn-primary setsvalues ajaxinput'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div>");
        });

        {{-- 添加attribute属性值  --}}
        $(document).on('click','.attrvalues',function(){       
            var aa = $(this).prev().prev().find('input').attr('name');
            var num = aa.substr(11,1);
            num = parseInt(num);
            $(this).next().css("display","inline");
            $(this).prev().after("<div class='form-group ajaxinput'><input type='text' class='form-control'  placeholder='属性值' name='attributes["+num+"][value][name][][name]'><button type='button' class='btn btn-outline btn-danger delete-column ajaxinput'><i class='glyphicon glyphicon-remove'></i></div>");            
        });

        {{-- 添加attribute属性行  --}}
        $(document).on('click','#attradd',function(){
            var aa = $("input[name^='attributes[']:last").attr('name');
            if(aa==undefined){
                $(".attributefirst").html("<div class='form-group form-inline attrs' id='attrkey_0'>属性名：<div class='form-group'><input class='form-control'  placeholder='属性名'  name='attributes[0][name]' ></div> 属性值：<div class='form-group' title='cannotremove'><input type='text' class='form-control'  placeholder='属性值'' name='attributes[0][value][name][][name]' ></div><button type='button' class='btn btn-primary attrvalues ajaxinput' >添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div><button type='button' class='btn btn-primary btn-lg btn-block' id='attradd'>添加attribute</button>");
                return;
            }
            var num = aa.substr(11,1);
            num = parseInt(num);
            num = num+1;
            $("#attrnum").val(num);
            $(".attrs").last().after("<div class='form-group form-inline attrs'>属性名：<div class='form-group'><input class='form-control'  placeholder='属性名'  name='attributes["+num+"][name]' ></div> 属性值：<div class='form-group'><input type='text' class='form-control'  placeholder='属性值' name='attributes["+num+"][value][name][][name]'></div><button type='button' class='btn btn-primary attrvalues ajaxinput'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div>");
        });

        {{-- 添加feature属性列  --}}
        $(document).on('click','.featurevalues',function(){
            var aa = $(this).prev().find('input').attr('name');
            var num = aa.substr(9,1);
            num = parseInt(num);
            $(this).next().css("display","inline");
            $(this).prev().after("<div class='form-group fhide_"+num+" fhides_"+num+" ajaxinput'><input type='text' class='form-control'  placeholder='属性值' name='features["+num+"][value][name][][name]'><button type='button' class='btn btn-outline btn-danger delete-column ajaxinput'><i class='glyphicon glyphicon-remove'></i></div>");            
        });

        {{-- 添加feature属性行  --}}
        $(document).on('click','#featureadd',function(){
            var aa = $("input[name^='features[']:last").attr('name');
            if(aa==undefined){
                $(".featurefirst").html("<div class='form-group form-inline features' id='featurekey_0'>类型：<select name='features[0][type]' class='form-control featype'><option value='1'>文本</option><option value='2'>单选</option><option value='3'>多选</option></select>属性名：<div class='form-group'><input class='form-control'  placeholder='属性名''  name='features[0][name]' ></div><div class='form-group fhide_0' title='cannotremove' style='display:none'>  属性值：<input type='text' class='form-control'  placeholder='属性值' name='features[0][value][name][][name]' ></div><button type='button' class='btn btn-primary featurevalues ajaxinput fhide_0' style='display:none'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div><button type='button' class='btn btn-primary btn-lg btn-block' id='featureadd'>添加feature</button>");
                return;
            }
            var num = aa.substr(9,1);
            num = parseInt(num);
            num = num+1;
            $("#featurenum").val(num);
            $(".features").last().after("<div class='form-group form-inline features'>类型：<select name='features["+num+"][type]' class='form-control featype'><option value='1'>文本</option><option value='2'>单选</option><option value='3'>多选</option></select>属性名：<div class='form-group '><input class='form-control'  placeholder='属性名'  name='features["+num+"][name]' ></div><div class='form-group fhide_"+num+" ' style='display:none' title='cannotremove'> 属性值：<input type='text' class='form-control'  placeholder='属性值' name='features["+num+"][value][name][][name]'></div><button type='button' class='btn btn-primary featurevalues ajaxinput fhide_"+num+"' style='display:none'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div></div>");
        });
    </script>
@stop