
@extends('layouts.default')
@section('content')
@foreach($fee_array as $name => $fee)
    <h4>{{$name}}:{{$fee}}$</h4>
@endforeach
@stop