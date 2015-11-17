@extends('layouts.base')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@stop

@section('css')
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">{{-- BOOTSTRAP CSS --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">{{-- OUR CSS --}}
    <link href="{{ asset('plugins/jqGrid/ui.jqgrid-bootstrap.css') }}" rel="stylesheet">{{-- OUR CSS --}}
@stop

@section('js')
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>{{-- JQuery --}}
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>{{-- BOOTSTRAP JS --}}
    <script src="{{ asset('plugins/jqGrid/jquery.jqGrid.min.js') }}"></script>{{-- BOOTSTRAP JS --}}
    <script src="{{ asset('plugins/jqGrid/grid.locale-en.js') }}"></script>{{-- BOOTSTRAP JS --}}
@stop

@section('body')
    @include('layouts.nav')
    <div class="container-fluid main">
        <div class="row">
            @section('breadcrumbs')@show{{-- 路径导航 --}}
            @section('content')@show{{-- 内容 --}}
        </div>
    </div>
@stop