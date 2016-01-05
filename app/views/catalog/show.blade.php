@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息 : </div>
        <div class="panel-body">
            <div class="col-lg-3">
                <strong>ID</strong>: {{ $catalog->id }}
            </div>
            <div class="col-lg-3">
                <strong>品类名称</strong>: {{ $catalog->name }}
            </div>
            <div class="col-lg-3">
                <strong>创建时间</strong>: {{ $catalog->created_at }}
            </div>
        </div>

        <div class="panel-heading">set属性: </div>
        <div class="panel-body">                          
                @foreach($catalog->sets as $set)
                    <div class="col-lg-12">
                        <div class="col-lg-1"><strong>{{ $set->name }}</strong>:</div>              
                        @foreach($set->values as $setvalue)
                            <div class="col-lg-1">{{ $setvalue->name }}</div>
                        @endforeach
                    </div>
                @endforeach
        </div>

        <div class="panel-heading">attribute属性 : </div>
        <div class="panel-body">                         
                @foreach($catalog->attributes as $attr)
                    <div class="col-lg-12">
                        <div class="col-lg-1"><strong>{{ $attr->name }}</strong>:</div>
                        @foreach($attr->values as $attrvalue)
                            <div class="col-lg-1">{{ $attrvalue->name }}</div>
                        @endforeach
                    </div>
                @endforeach
        </div>

        <div class="panel-heading">feature属性 : </div>
        <div class="panel-body">                              
                @foreach($catalog->features as $feature)
                <div class="col-lg-12">
                    <div class="col-lg-1"><strong>{{ $feature->name }}</strong>:</div>
                        @foreach($feature->values as $featurevalue)
                            <div class="col-lg-1">{{ $featurevalue->name }}</div>
                        @endforeach
                    </div>
                @endforeach
        </div>
    </div>

@stop