@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('logisticsRule.update', ['id' => $model->id]) }} @stop
@section('formAttributes') name='creator'@stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
        <div class="form-group col-lg-3">
            <label for="name" class="control-label">名称</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="name" placeholder="名称" name='name' value="{{ old('name') ? $old('name') : $model->name }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="type_id">物流方式</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <select name="type_id" class="form-control" id="type_id">
                @foreach($logisticses as $logistics)
                    <option value="{{$logistics->id}}" {{ $model->type_id == $logistics->id ? 'selected' : '' }}>
                        {{$logistics->type}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3">
            <label for="priority" class="control-label">优先级</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="priority" placeholder="优先级" name='priority' value="{{ old('priority') ? old('priority') : $model->priority }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="is_clearance">是否通关</label>
            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="1" {{old('is_clearance') ? (old('is_clearance') == '1' ? 'checked' : '') : ($model->is_clearance == '1' ? 'checked' : '')}}>是
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="is_clearance" value="0" {{old('is_clearance') ? (old('is_clearance') == '0' ? 'checked' : '') : ($model->is_clearance == '0' ? 'checked' : '')}}>否
                </label>
            </div>
        </div>
    </div>

    <div class="modal fade" id="catalogs" tabindex="-1" role="dialog" 
       aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                       品类选择
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class='form-group row'>
                        @foreach($catalogs_outer as $catalog)
                            <div class='col-lg-3'>
                                <input type='checkbox' name='catalogs[]' value="{{ $catalog->id }}" {{ $model->innerType('catalog', $catalog->id) ? 'checked' : ''}}><font size='3px'>{{ $catalog->name }}</font>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="channels" tabindex="-1" role="dialog" 
       aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                       渠道选择
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class='form-group row'>
                        @foreach($channels_outer as $channel)
                            <div class='col-lg-3'>
                                <input type='checkbox' name='channels[]' value="{{ $channel->id }}" {{ $model->innerType('channel', $channel->id) ? 'checked' : ''}}><font size='3px'>{{ $channel->name }}</font>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="countrys" tabindex="-1" role="dialog" 
       aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                       发货国家
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        @foreach($countrySorts as $countrySort)
                        <div class='form-group'>
                            <div class='col-lg-12'>
                                <font size='3px' color='blue'>{{$countrySort->name}}</font>
                                <button type='button' class='btn btn-info all_select'>全选</button>
                                <button type='button' class='btn btn-info opposite_select'>反选</button>
                            </div>
                            @foreach($countrySort->countries as $country)
                            <div class='col-lg-4'>
                                <input type='checkbox' name='countrys[]' value="{{ $country->id }}" {{ $model->innerType('country', $country->id) ? 'checked' : ''}}><font size='3px'>{{ $country->cn_name }}</font>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logistics_limit" tabindex="-1" role="dialog" 
       aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                       物流限制
                    </h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        @foreach($logisticsLimits_outer as $key => $logisticsLimit)
                            <div class='form-group row'>
                                <label>{{ $logisticsLimit->name }}:</label>
                                <input type='radio' name="limits[{{$logisticsLimit->id}}]" value="0" {{ $model->innerType('limit', $logisticsLimit->id, '0') ? 'checked' : ''}}>含
                                <input type='radio' name="limits[{{$logisticsLimit->id}}]" value="1" {{ $model->innerType('limit', $logisticsLimit->id, '1') ? 'checked' : ''}}>不含
                                <input type='radio' name="limits[{{$logisticsLimit->id}}]" value="2" {{ $model->innerType('limit', $logisticsLimit->id, '2') ? 'checked' : ''}}>可以含
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="weight_from" class="control-label">起始重量(kg)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight_from" placeholder="重量从" name='weight_from' value="{{ old('weight_from') ? old('weight_from') : $model->weight_from }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="weight_to" class="control-label">结束重量至(kg)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="weight_to" placeholder="重量至" name='weight_to' value="{{ old('weight_to') ? old('weight_to') : $model->weight_to }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="order_amount_from" class="control-label">起始订单金额($)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="order_amount_from" placeholder="订单金额" name='order_amount_from' value="{{ old('order_amount_from') ? old('order_amount_from') : $model->order_amount_from }}">
        </div>
        <div class="form-group col-lg-3">
            <label for="order_amount_to" class="control-label">结束订单金额($)</label><small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input class="form-control" id="order_amount_to" placeholder="订单金额" name='order_amount_to' value="{{ old('order_amount_to') ? old('order_amount_to') : $model->order_amount_to }}">
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-lg-3">
            <label for="catalogs" class="control-label">产品分类:</label>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#catalogs">产品分类</button>
        </div>
        <div class="form-group col-lg-3">
            <label for="channels" class="control-label">订单来源渠道:</label>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#channels">订单来源渠道</button>
        </div>
        <div class="form-group col-lg-3">
            <label for="countrys" class="control-label">发货国家:</label>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#countrys">发货国家</button>
        </div>
        <div class="form-group col-lg-3">
            <label for="countrys" class="control-label">物流限制:</label>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#logistics_limit">物流限制</button>
        </div>
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '.all_select', function(){
            block = $(this).parent().parent();
            block.find("input[type='checkbox']").prop('checked', true);
        });

        $(document).on('click', '.opposite_select', function(){
            block = $(this).parent().parent();
            block.find("input[type='checkbox']").prop('checked', false);
        });
    });
</script>