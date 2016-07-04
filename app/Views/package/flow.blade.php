@extends('common.detail')
@section('detailBody')
    <div class="text-center">
        <div class="row">
            <div class="col-lg-4">
                <a type="button" class="btn btn-info" href="{{ route('order.putNeedQueue') }}">
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
                <a type="button" class="btn btn-warning" href="javascript:" disabled>
                    自动分配物流 <span class="badge">{{ $assignNum }}</span>
                </a>
            </div>
            <div class="col-lg-2 text-left">
                <a type="button" class="btn btn-default" href="{{ route('package.manualLogistics') }}">
                    手动分配物流 <span class="badge">{{ $assignFailed }}</span>
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
                <a type="button" class="btn btn-warning" href="javascript:" disabled>
                    物流商下单 <span class="badge">{{ $placeNum }}</span>
                </a>
            </div>
            <div class="col-lg-2 text-left">
                <a type="button" class="btn btn-default" href="{{ route('package.manualShipping') }}">
                    手工发货 <span class="badge">{{ $manualShip }}</span>
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
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'print']) }}">
                    打印拣货单 <span class="badge">{{ $printNum }}</span>
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
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'single']) }}">
                    单单包装 <span class="badge">{{ $singlePack }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'singleMulti']) }}">
                    单多包装 <span class="badge">{{ $singleMultiPack }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'inbox']) }}">
                    多多分拣 <span class="badge">{{ $multiInbox }}</span>
                </a>
                <a type="button" class="btn btn-primary" href="{{ route('pickList.indexPrintPickList', ['content' => 'multi']) }}">
                    多多包装 <span class="badge">{{ $multiPack }}</span>
                </a>
            </div>
            <div class="col-lg-3 text-left">
                <a type="button" class="btn btn-default" href="{{ route('pickList.oldPrint') }}">
                    原面单重新打印 <span class="badge">0</span>
                </a>
                <a type="button" class="btn btn-default" href="{{ route('pickList.updatePrint') }}">
                    更换物流面单 <span class="badge">0</span>
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
                <a type="button" class="btn btn-success" href="{{ route('package.shipping') }}">
                    执行发货 <span class="badge">{{ $packageShipping }}</span>
                </a>
            </div>
            <div class="col-lg-2 text-left">
                <a type="button" class="btn btn-default" href="{{ route('errorList.index') }}">
                    异常拣货单处理 <span class="badge">{{ $packageException }}</span>
                </a>
            </div>
        </div>
    </div>
@stop