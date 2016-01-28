@extends('common.detail')

<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}

@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">基本信息</div>
        <div class="panel-body">
            <div class="col-lg-4">
                <strong>ID</strong>: {{ $zone->id }}
            </div>
            <div class="col-lg-4">
                <strong>物流分区</strong>: {{ $zone->zone }}
            </div>
            <div class="col-lg-4">
                <strong>物流方式</strong>: {{ $zone->logistics->logistics_type }}
            </div>
            <div class="col-lg-4">
                <strong>国家</strong>: {{ $zone->country_id }}
            </div>
            <div class="col-lg-4">
                <strong>种类</strong>: {{ $zone->shipping_id == 'express' ? '快递' : '小包' }}
            </div>
            <div class="col-lg-4" id="express">
                <strong>首重(kg)</strong>: {{ $zone->fixed_weight }}
            </div>
            <div class="col-lg-4" id="express">
                <strong>首重价格(/kg)</strong>: {{ $zone->fixed_price }}
            </div>
            <div class="col-lg-4" id="express">
                <strong>续重(kg)</strong>: {{ $zone->continued_weight }}
            </div>
            <div class="col-lg-4" id="express">
                <strong>续重价格(/kg)</strong>: {{ $zone->continued_price }}
            </div>
            <div class="col-lg-4" id="express">
                <strong>其他固定费用</strong>: {{ $zone->other_fixed_price }}
            </div>
            <div class="col-lg-4" id="express">
                <strong>其他比例费用(%)</strong>: {{ $zone->other_scale_price }}
            </div>
            <div class="col-lg-4" id="packet">
                <strong>价格(/kg)</strong>: {{ $zone->price }}
            </div>
            <div class="col-lg-4" id="packet">
                <strong>其他费用</strong>: {{ $zone->other_price }}
            </div>
            <div class="col-lg-4">
                <strong>最后折扣</strong>: {{ $zone->discount }}
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">日志信息</div>
        <div class="panel-body">
            <div class="col-lg-6">
                <strong>创建时间</strong>: {{ $zone->created_at }}
            </div>
            <div class="col-lg-6">
                <strong>更新时间</strong>: {{ $zone->updated_at }}
            </div>
        </div>
    </div>
@stop
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url : "{{ route('zoneShipping') }}",
            data : { id : '{{ $zone->id }}' },
            dataType : 'json',
            type : 'get',
            success : function(result) {
                if (result == 'express') {
                    $("div#express").show();
                    $("div#packet").hide();
                }else {
                    $("div#packet").show();
                    $("div#express").hide();
                }
            }
        });
    });
</script>