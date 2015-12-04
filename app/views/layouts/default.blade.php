@extends('layouts.base')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@stop
@section('css')
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">{{-- BOOTSTRAP CSS --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">{{-- OUR CSS --}}
     
@stop
@section('js')
    {{--<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>--}}{{-- JQuery --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
     
@stop
@section('init')
    <script type="text/javascript">
        {{-- CSRF token for AJAX --}}
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
   
@stop
@section('body')
    @include('layouts.nav')
    <div class="container-fluid main">
        <div class="row">
            <div class="col-lg-2">
                @include('layouts.sidebar')
            </div>
            <div class="col-lg-10">
                @section('breadcrumbs')@show{{-- 路径导航 --}}
                @section('content')@show{{-- 内容 --}}
            </div>
        </div>
    </div>
@stop