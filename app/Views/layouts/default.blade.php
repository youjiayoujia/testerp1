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
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>{{-- BOOTSTRAP JS --}}
    <script src="{{ asset('js/uri.min.js') }}"></script>{{-- JS URI --}}

    <script src="{{ asset('js/jquery.cxcalendar.min.js') }}"></script>

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
        @if(isset($sidebar))
            <div class="row">
                <div class="col-lg-2">
                    @include('layouts.sidebar')
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-{{ isset($sidebar) ? '10' : '12' }}">
                <ol class="breadcrumb">
                    <li><a href="/">主页</a></li>
                    @section('breadcrumbs')
                        @if(isset($metas['mainTitle']))
                            <li><a href="{{ $metas['mainIndex'] }}">{{ $metas['mainTitle'] }}</a></li>
                        @endif
                        @if(isset($metas['title']))
                            <li class="active">{{ $metas['title'] }}</li>
                        @endif
                    @show{{-- 路径导航 --}}
                </ol>
                @if(session('alert'))
                    {!! session('alert') !!}
                @endif
                @section('content')@show{{-- 内容 --}}
            </div>
        </div>
    </div>
@stop