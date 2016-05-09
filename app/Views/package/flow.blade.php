@extends('common.detail')
@section('detailBody')
    <div class="text-center">
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-info" href="{{ route('package.doPackage') }}">
                    Do Package <span class="badge">{{ $packageNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-warning" href="{{ route('package.assignLogistics') }}">
                    自动分配物流 <span class="badge">{{ $assignNum }}</span>
                </a>
            </div>
            <div class="col-lg-2 text-left">
                <a type="button" class="btn btn-default" href="{{ route('pickList.createPick') }}">
                    手动分配物流 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-warning" href="{{ route('package.placeLogistics') }}">
                    物流商下单 <span class="badge">{{ $placeNum }}</span>
                </a>
            </div>
            <div class="col-lg-2 text-left">
                <a type="button" class="btn btn-default" href="{{ route('pickList.createPick') }}">
                    手工发货 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-primary" href="{{ route('pickList.createPick') }}">
                    生成拣货单 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-primary" href="{{ route('pickList.createPick') }}">
                    打印拣货单 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-primary" href="{{ route('pickList.createPick') }}">
                    单单包装 <span class="badge">{{ $pickNum }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.createPick') }}">
                    单多包装 <span class="badge">{{ $pickNum }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.createPick') }}">
                    多多分拣 <span class="badge">{{ $pickNum }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.createPick') }}">
                    多多包装 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
            <div class="col-lg-3 text-left">
                <a type="button" class="btn btn-default" href="{{ route('pickList.createPick') }}">
                    原面单重新打印 <span class="badge">{{ $pickNum }}</span>
                </a>
                <a type="button" class="btn btn-default" href="{{ route('pickList.createPick') }}">
                    更换物流面单 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <i class="glyphicon glyphicon-arrow-down"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-success" href="{{ route('pickList.createPick') }}">
                    执行发货 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
            <div class="col-lg-2 text-left">
                <a type="button" class="btn btn-default" href="{{ route('pickList.createPick') }}">
                    异常拣货单处理 <span class="badge">{{ $pickNum }}</span>
                </a>
            </div>
        </div>
    </div>
@stop