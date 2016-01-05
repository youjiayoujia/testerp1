@extends('layouts.default')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">分类详情 : </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $catalog->id }}</dd>
                <dt>分类名称</dt>
                <dd>{{ $catalog->name }}</dd>
                <dt>创建时间</dt>
                <dd>{{ $catalog->created_at }}</dd>
                <dt>更新时间</dt>
                <dd>{{ $catalog->updated_at }}</dd>
            </dl>
        </div>

        <div class="panel-heading">set属性: </div>
        <div class="panel-body">
            <dl class="dl-horizontal">               
                @foreach($catalog->sets as $set)
                    <dt>{{ $set->name }}</dt>                    
                        @foreach($set->values as $setvalue)
                            <dd>{{ $setvalue->name }}</dd>
                        @endforeach
                @endforeach
            </dl>
        </div>

        <div class="panel-heading">attribute属性 : </div>
        <div class="panel-body">
            <dl class="dl-horizontal">                         
                @foreach($catalog->attributes as $attr)
                    <dt>{{ $attr->name }}</dt>
                    @foreach($attr->values as $attrvalue)
                        <dd>{{ $attrvalue->name }}</dd>
                    @endforeach
                @endforeach
            </dl>
        </div>

        <div class="panel-heading">feature属性 : </div>
        <div class="panel-body">
            <dl class="dl-horizontal">                              
                @foreach($catalog->features as $feature)
                    <dt>{{ $feature->name }}</dt>
                    @foreach($feature->values as $featurevalue)
                        <dd>{{ $featurevalue->name }}</dd>
                    @endforeach
                @endforeach
            </dl>
        </div>
    </div>

@stop