@extends('layouts.default')
@section('content')
    <div class="table-responsive">
        <table id="jqGrid"></table>
        <div id="jqGridPager"></div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {

            $("#jqGrid").jqGrid({
                url: 'http://trirand.com/blog/phpjqgrid/examples/jsonp/getjsonp.php?callback=?&qwery=longorders',
                mtype: "GET",
                styleUI: 'Bootstrap',
                datatype: "jsonp",
                colModel: [
                    {label: 'OrderID', name: 'OrderID', key: true, width: 75},
                    {label: 'Customer ID', name: 'CustomerID', width: 150},
                    {label: 'Order Date', name: 'OrderDate', width: 150},
                    {label: 'Freight', name: 'Freight', width: 150},
                    {label: 'Ship Name', name: 'ShipName', width: 150}
                ],
                viewrecords: true,
                width: '100%',
                rowNum: 20,
                pager: "#jqGridPager"
            });
        });

    </script>
@stop
