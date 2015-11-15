@section('hacker_header')
@show{{-- 黑客头部申明区域 --}}

        <!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>@section('title') 南京快悦ERP @show{{-- 页面标题 --}}</title>
    <meta name="description" content="{{ isset($description) ? $description : 'YASCMF AdminLTE' }}"/>
    <meta name="keywords" content="YASCMF,AdminLTE,{{ Cache::get('website_keywords') }}"/>
    <meta name="author" content="{{ Cache::get('system_author_website') }}"/>
    <meta name="renderer" content="webkit">{{-- 360浏览器使用webkit内核渲染页面 --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>{{-- IE(内核)浏览器优先使用高版本内核 --}}

    @section('meta')
    @show{{-- 添加一些额外的META申明 --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">{{-- favicon --}}

    @section('head_css')
    @show{{-- head区域css样式表 --}}

    @section('head_js')
    @show{{-- head区域javscript脚本 --}}

    @section('init')
    @show{{-- 初始化框架 --}}

    @section('doc_begin')
    @show{{-- 开始之前加载 --}}

</head>
<body @section('body_attr')class=""@show{{-- 追加类属性 --}}>
<!--[if lte IE 7]>
<div id="errorie">
    <div>您还在使用老掉牙的IE，正常使用系统前请升级您的浏览器到 IE8以上版本 <a target="_blank"
                                                 href="http://windows.microsoft.com/zh-cn/internet-explorer/ie-8-worldwide-languages">点击升级</a>&nbsp;&nbsp;强烈建议您更改换浏览器：<a
            href="http://down.tech.sina.com.cn/content/40975.html" target="_blank">谷歌 Chrome</a></div>
</div>
<![endif]-->

@section('beforeBody')
@show{{--在正文之前填充一些东西 --}}

@section('body')
@show{{-- 正文部分 --}}

@section('afterBody')
@show{{-- 在正文之后填充一些东西，比如统计代码之类的东东 --}}
</body>
</html>

@section('hacker_footer')
@show{{-- 黑客尾部申明区域 --}}
