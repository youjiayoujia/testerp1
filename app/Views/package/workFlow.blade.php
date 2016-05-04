@extends('common.detail')
@section('detailBody')
    <a type="button" class="btn btn-info" href="{{ route('package.doPackage') }}">
        Do Package <span class="badge">{{ $packageNum }}</span>
    </a>
    <i class="glyphicon glyphicon-arrow-right"></i>
    <a type="button" class="btn btn-warning" href="{{ route('package.assignLogistics') }}">
        Assign Logistics <span class="badge">{{ $assignNum }}</span>
    </a>
    <i class="glyphicon glyphicon-arrow-right"></i>
    <a type="button" class="btn btn-warning" href="{{ route('package.placeLogistics') }}">
        Place Logistics <span class="badge">{{ $placeNum }}</span>
    </a>
    <i class="glyphicon glyphicon-arrow-right"></i>
    <a type="button" class="btn btn-primary" href="{{ route('pickList.createPick') }}">
        Pick <span class="badge">{{ $pickNum }}</span>
    </a>
@stop