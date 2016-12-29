@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading"><strong>筛选条件</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <input class="form-control" id="sku" placeholder="SKU" value="" name="sku">
                </div>
                <div class="col-lg-2">
                    <input class="form-control" id="site" placeholder="站点" value="" name="site">
                </div>
                <div class="col-lg-1">
                    <button class="filter">查询</button>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><a href="{{ route('sku.saleReport') }}"></a>EbaySku销量报表</div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr>
                        <td>{{ $data['logisticsName'] }}</td>
                        <td>{{ $data['logisticsId'] }}</td>
                        <td>{{ $data['logisticsPriority'] }}</td>
                        <td>{{ $data['quantity'] }}</td>
                        <td>{{ $data['weight'] }}</td>
                        <td>{{ $data['percent'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-center" colspan="6">
                        {{ '当前包裹数:' . $count . ' 当前总重:' . $totalWeight . 'Kg' }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type='text/javascript'>
    $(document).ready(function(){
        $('#start').cxCalendar();
        $('#end').cxCalendar();
    });

    $(document).on('click', '.filter', function () {
        var start = $('#start').val();
        var end = $('#end').val();
        if (start && end) {
            location.href="{{route('package.logisticsDelivery')}}/?start="+start+"&end="+end;
        }
    });
</script>