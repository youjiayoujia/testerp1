@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">ID</th>
    <th>订单号</th>
    <th>订单金额</th>
    <th>仓库</th>
    <th>收货人</th>
    <th>国家</th>
    <th>状态</th>
    <th>类型</th>
    <th>重量(kg)</th>
    <th>物流方式</th>
    <th>物流单号</th>
    <th>发货类型</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $package)
        <tr class="dark-{{ $package->status_color }}">
            <td>
                <input type='checkbox' name='single[]' class='single'>
                @if(($package->order ? $package->order->packages->count() : 0) > 1)
                    <span class='glyphicon glyphicon-adjust'></span>
                @endif
            </td>
            <td class='packageId' data-id="{{ $package->id }}">{{ $package->id }}</td>
            <td>{{ $package->order ? $package->order->ordernum : '订单号有误' }}</td>
            <td>{{ $package->order ? $package->order->amount . $package->order->currency : '订单金额有误' }}</td>
            <td>{{ $package->warehouse ? $package->warehouse->name : '' }}</td>
            <td>{{ $package->shipping_firstname . $package->shipping_lastname }}</td>
            <td>{{ $package->shipping_country }}</td>
            <td>
                <button class="btn btn-{{ $package->status_color }} btn-xs">
                    {{ $package->status_name }}
                </button>
                @if($package->status == 'PROCESSING')
                    @if(empty($package->items->first()->warehouse_position_id))
                        <font color='red'>(需匹配库存)</font>
                    @endif
                @endif
            </td>
            <td>{{ $package->type == 'SINGLE' ? '单单' : ($package->type == 'SINGLEMULTI' ? '单多' : '多多') }}</td>
            <td>{{ $package->weight }}</td>
            <td class='logisticsReal'>{{ $package->logistics ? $package->logistics->code : '' }}</td>
            <td>{{ $package->tracking_no }}</td>
            <td>{{ $package->is_auto ? '自动' : '手动' }}</td>
            <td>{{ $package->created_at }}</td>
            <td>
                <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target=".packageDetails{{$package->id}}" aria-expanded="false" aria-controls="collapseExample" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span>
                </button>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $package->table }}" data-id="{{$package->id}}" title='日志'>
                    <span class="glyphicon glyphicon-road"></span>
                </button>
            </td>
        </tr>
        @foreach($package->items as $key => $packageItem)
            <tr class="{{ $package->status_color }} packageDetails{{$package->id}} fb">
                @if($key == 0)
                    <td colspan='2' rowspan="{{$package->items->count()}}">
                        <p>{{ $package->shipping_firstname . $package->shipping_lastname }}</p>
                        <p>{{ $package->shipping_shipping_address . ' ' .$package->shipping_city . ' ' . $package->shiping_state . ' ' . $package->shipping_country }}
                    </td>
                    <td colspan='3' rowspan="{{$package->items->count()}}">包裹item信息</td>
                @endif
                <td>sku</td>
                <td colspan='2'>
                    <button class="btn btn-warning btn-xs sku_search"
                            data-toggle="modal"
                            data-target="#sku_search">
                        {{ $packageItem->item ? $packageItem->item->sku : '' }}
                    </button>
                </td>
                <td>库位</td>
                <td colspan='2'>{{ $packageItem->warehousePosition ? $packageItem->warehousePosition->name : '' }}</td>
                <td>数量</td>
                <td colspan='3'>{{ $packageItem->quantity }}</td>
            </tr>
        @endforeach
        <tr class="{{ $package->status_color }} packageDetails{{$package->id}} fb">
            <td colspan='4'>渠道: {{ $package->channel ? $package->channel->name : '无渠道'}}</td>
            <td colspan='4'>拣货单: {{ $package->picklist ? $package->picklist->picknum : '暂无拣货单信息'}}</td>
            <td colspan='2'>运输方式: {{ $package->order->shipping }}</td>
            <td colspan='5'>
                <a href="{{ route('package.show', ['id' => $package->id]) }}" class="btn btn-info btn-xs" title='查看'>
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                @if($package->status == 'ASSIGNED' || $package->status == 'TRACKINGFAILED')
                    <a href="javascript:" data-id="{{ $package->id }}" class="btn btn-primary btn-xs recycle" title='重新匹配物流'>
                        <span class="glyphicon glyphicon-random"></span>
                    </a>
                @endif
                @if(in_array($package->status,['PROCESSING','PICKING','PACKED']))
                    <a href="javascript:" data-id="{{ $package->id }}" class="btn btn-primary btn-xs retrack" title='重新物流下单'>
                        <span class="glyphicon glyphicon-refresh"></span>
                    </a>
                @endif
                <a href="{{ route('package.editTrackingNo', ['id'=>$package->id]) }}" class="btn btn-primary btn-xs" title='修改追踪号'>
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <button class="btn btn-primary btn-xs split"
                        data-toggle="modal"
                        data-target="#split" data-id="{{ $package->id }}" title='拆分包裹'>
                    <span class="glyphicon glyphicon-tasks"></span>
                </button>
            </td>
        </tr>
    @endforeach
    <div class="modal fade" id="sku_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">sku库存信息</div>
                    <div class="panel-body">
                        <div class='buf'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="split" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">拆分包裹</div>
                    <div class="panel-body">
                        <div class='row'>
                            <div class='col-lg-5'>
                                <input type='text' class='form-control package_num' placeholder='需要拆分的包裹数'>
                            </div>
                            <div class='col-lg-1'>
                                <button type='button' class='btn btn-primary confirm_quantity' name=''>确认</button>
                            </div>
                        </div>
                        <div class='split_package'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="change_logistics" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-default">
                    <div class="panel-heading">批量修改物流方式</div>
                    <div class="panel-body">
                        <div class='col-lg-12'>
                            <select name='change_logistics' class='form-control change_logistics col-lg-4'>
                                @foreach($logisticses as $logistics)
                                    <option value="{{ $logistics->id }}">{{ $logistics->code }}</option>
                                @endforeach
                            </select>
                            <button type='button' class='btn btn-primary submit_logistics'>确认</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('tableToolButtons')
    <div class="btn-group">
        <a class="btn btn-success implodePackage" href="javascript:">
            合并包裹
        </a>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            展示类型
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class='easy' data-type='easy'>简洁</a></li>
            <li><a href="javascript:" class='easy' data-type='full'>全貌</a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="javascript:" class='returnTrackno' data-status='1'>回传运单号</a></li>
            <li><a href="javascript:" class='returnFee' data-type='1'>回传一次运费</a></li>
            <li><a href="javascript:" class='returnFee' data-type='2'>回传二次运费</a></li>
            <li><a href="javascript:" class='multiEditTracking' data-type='3'>批量修改追踪号</a></li>
            <li><a data-toggle="modal"
                   data-target="#change_logistics">
                    批量修改物流方式
                </a></li>
            <li><a href="javascript:" class='remove_logistics'>批量清除追踪号</a></li>
            <li><a href="javascript:" class='remove_packages'>批量取消包裹</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn btn-success export" href="javascript:">
            批量导出手工发货package信息
        </a>
    </div>
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route('package.shipping') }}">
            出库复检
        </a>
    </div>
    <div class="btn-group">
        <a class="btn btn-success" href="{{ route('package.shippingStatistics') }}">
            发货统计
        </a>
    </div>
@stop
@section('childJs')
    <script type='text/javascript'>
        $(document).on('click', '.easy', function () {
            type = $(this).data('type');
            if (type == 'easy') {
                $('.fb').hide();
            } else {
                $('.fb').show();
            }
        });

        $('.change_logistics').select2();

        $(document).on('click', '.sku_search', function () {
            $('.buf').html('');
            sku = $.trim($(this).text());
            if (sku) {
                $.get(
                        "{{ route('stock.getSingleSku')}}",
                        {sku: sku, type: '1'},
                        function (result) {
                            if (result == 'false') {
                                alert('sku不存在');
                                return false;
                            }
                            $('.buf').html('');
                            $('.buf').html(result);
                        }
                );
            }
        });

        $(document).ready(function () {

            arr = new Array();
            i = 0;
            $.each($('.packageId'), function () {
                arr[i] = $(this).data('id');
                i++;
            })
            $.get(
                    "{{ route('package.ajaxRealTime')}}",
                    {'arr': arr},
                    function (result) {
                        j = 0;
                        $.each($('.packageId'), function () {
                            block = $(this).parent();
                            logisticsReal = block.children('.logisticsReal');
                            logisticsReal.html(logisticsReal.text() + "   <font color='gray'>" + result[j] + "</font>");
                            j++;
                        })
                    }
            )

            $('.returnTrackno').click(function () {
                location.href = "{{ route('package.returnTrackno')}}";
            });

            $('.returnFee').click(function () {
                type = $(this).data('type');
                location.href = "{{ route('package.returnFee')}}?type=" + type;
            })

            $('.multiEditTracking').click(function () {
                type = $(this).data('type');
                location.href = "{{ route('package.returnFee')}}?type=" + type;
            })

            $(document).on('click', '.recycle', function () {
                id = $(this).data('id');
                if (confirm('确认重新匹配物流？')) {
                    location.href = "{{ route('package.recycle') }}?id=" + id;
                }
            })

            $(document).on('click', '.retrack', function () {
                id = $(this).data('id');
                if (confirm('确认重下物流单？')) {
                    location.href = "{{ route('package.retrack') }}?id=" + id;
                }
            })

            $(document).on('click', '.submit_logistics', function () {
                if (confirm('确认修改物流方式?')) {
                    arr = new Array();
                    i = 0;
                    $.each($('.single:checked'), function () {
                        tmp = $(this).parent().next().text();
                        arr[i] = tmp;
                        i++;
                    })
                    logistics_id = $('.change_logistics').val();
                    if (arr.length) {
                        location.href = "{{ route('package.changeLogistics', ['arr' => '']) }}/" + arr + '/' + logistics_id;
                    } else {
                        alert('请选择包裹信息');
                    }
                }
            });

            $(document).on('click', '.remove_packages', function () {
                if (confirm('确认删除包裹?')) {
                    arr = new Array();
                    i = 0;
                    $.each($('.single:checked'), function () {
                        tmp = $(this).parent().next().text();
                        arr[i] = tmp;
                        i++;
                    })
                    if (arr.length) {
                        location.href = "{{ route('package.removePackages', ['arr' => '']) }}/" + arr;
                    } else {
                        alert('请选择包裹信息');
                    }
                }
            });

            $(document).on('click', '.remove_logistics', function () {
                if (confirm('确认清空挂号码?')) {
                    arr = new Array();
                    i = 0;
                    $.each($('.single:checked'), function () {
                        tmp = $(this).parent().next().text();
                        arr[i] = tmp;
                        i++;
                    })
                    if (arr.length) {
                        location.href = "{{ route('package.removeLogistics', ['arr' => '']) }}/" + arr;
                    } else {
                        alert('请选择包裹信息');
                    }
                }
            });

            $(document).on('click', '.split_button', function () {
                if (confirm('确认拆分')) {
                    id = $(this).parent().prev().find('.confirm_quantity').attr('name');
                    arr = new Array();
                    i = 0;
                    j = 0;
                    $.each($(this).parent().find('table'), function (k, v) {
                        $.each($(v).find('tr'), function (k1, v1) {
                            if ($(v1).find(':radio').prop('checked')) {
                                arr[i] = j + '.' + $(v1).find('.item_id').data('itemid');
                                i += 1;
                            }
                        })
                        j += 1;
                    })
                    location.href = "{{ route('package.actSplitPackage', ['arr' => '']) }}/" + arr + "/" + id;
                }
            })

            $(document).on('click', '.confirm_quantity', function () {
                quantity = $(this).parent().prev().find(':input').val();
                id = $(this).attr('name');
                if (quantity > 1) {
                    $.get(
                            "{{ route('package.returnSplitPackage')}}",
                            {quantity: quantity, id: id},
                            function (result) {
                                $('.split_package').html('');
                                $('.split_package').html(result);
                            }, 'html'
                    );
                } else {
                    alert('数量不能小于1');
                }

            })

            $(document).on('click', '.split', function () {
                id = $(this).data('id');
                $('.confirm_quantity').attr('name', id);
                $('.package_num').val('');
                $('.split_package').html('');
            })

            $(document).on('click', '.implodePackage', function () {
                arr = new Array();
                i = 0;
                $.each($('.single:checked'), function () {
                    tmp = $(this).parent().next().text();
                    arr[i] = tmp;
                    i++;
                })
                if (arr.length) {
                    if (confirm('确认合并包裹')) {
                        location.href = "{{ route('package.implodePackage', ['arr' => '']) }}/" + arr;
                    }
                } else {
                    alert('未选择包裹信息');
                }
            })

            $('.export').click(function () {
                arr = new Array();
                i = 0;
                $.each($('.single:checked'), function () {
                    tmp = $(this).parent().next().text();
                    arr[i] = tmp;
                    i++;
                })
                if (arr.length) {
                    location.href = "{{ route('package.exportManualPackage') }}?arr=" + arr.join('|');
                } else {
                    alert('未选择包裹信息');
                }
            });

            $('.select_all').click(function () {
                if ($(this).prop('checked') == true) {
                    $('.single').prop('checked', true);
                } else {
                    $('.single').prop('checked', false);
                }
            });

            $('.send').click(function () {
                id = $(this).data('id');
                $.ajax({
                    url: "{{ route('package.ajaxPackageSend')}}",
                    data: {'id': id},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        location.reload();
                    }
                });
            });
        });
    </script>
@stop
