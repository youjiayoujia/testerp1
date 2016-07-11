@extends('common.form')
@section('formAction') {{ route('pickList.createPickStore') }} @stop
@section('formBody')
    <table class='table table-bordered'>
        <tbody>
        <tr><td>该仓库目前共有可拣货包裹{{$count}}个。请选择需要拣货的包裹，生成拣货单，系统将自动分组成多张。</td></tr>
        </tbody>
    </table>
    <table class='table'>
        <tr>
            <td class='col-lg-1'>销售渠道</td>
            <td class='row'>
            <input type='checkbox' name='channel' class='channel_all' checked='true'>全部
            @foreach($channels as $channel)
                <input type='checkbox' name='channel[]' class='channel' value="{{$channel->id}}" checked='true'>{{$channel->name}}
            @endforeach
            </td>
        </tr>
        <tr>
            <td class='col-lg-1'>包裹类型</td>
            <td class='row'>
            <input type='checkbox' class='package_all' checked='true'>全部
            <input type='checkbox' name='package[]' class='package' value='SINGLE' checked='true'>单单
            <input type='checkbox' name='package[]' class='package' value='SINGLEMULTI' checked='true'>单多
            <input type='checkbox' name='package[]' class='package' value='MULTI' checked='true'>多多
            </td>
        </tr>
    </table>
    <div class='row'>
        <div class='col-lg-3'>
        <table class='table'>
            <tr>
                <td>邮递方式</td>
            </tr>
            <tr>
                <td><input type='checkbox' class='logistics_all' checked='true'>全部</td>
            </tr>
            <tr>
                <td><input type='checkbox' class='mixed'>混合物流</td>
            </tr>
            @foreach($logisticses as $logistics)
            <tr>
                <td><input type='checkbox' name='logistics[]' class='logistics' value="{{$logistics->id}}" checked='true'>{{$logistics->code}}</td>
            </tr>
            @endforeach
        </table>
        </div>
        <div class='col-lg-9'>
        <table class='table table-bordered table-striped'>
            <tr>
                <td>包裹数</td>
                <td></td>
            </tr>
            <tr>
                <td>订单数</td>
                <td></td>
            </tr>
        </table>
        <table class='table table-bordered table-striped'>
            <tr>
                <td>单单/单多拣货数：</td>
                <td><input type='text' name='singletext' class='form-control' value='25'></td>
            </tr>
            <tr>
                <td>多多拣货数</td>
                <td><input type='text' name='multitext' class='form-control' value='20'></td>
            </tr>
        </table>
        </div>
    </div>
@stop
@section('formButton')
    <button type="submit" class="btn btn-success">生成拣货单</button>
@stop
@section('pageJs')
<script type='text/javascript'>
$(document).ready(function(){
    $('.mixed').click(function(){
        if($(this).prop('checked') == true) {
            $('.logistics').prop('checked', false);
            $('.logistics').prop('disabled', true);
            $('.logistics_all').prop('checked', false);
            $('.logistics_all').prop('disabled', true);
        } else {
            $('.logistics').prop('checked', false);
            $('.logistics').prop('disabled', false);
            $('.logistics_all').prop('checked', false);
            $('.logistics_all').prop('disabled', false);
        }
    });

    $('.channel_all').click(function(){
        if($(this).prop('checked') == true)
            $('.channel').prop('checked', true);
        else
            $('.channel').prop('checked', false);
    });

    $('.package_all').click(function(){
        if($(this).prop('checked') == true)
            $('.package').prop('checked', true);
        else
            $('.package').prop('checked', false);
    });

    $('.logistics_all').click(function(){
        if($(this).prop('checked') == true)
            $('.logistics').prop('checked', true);
        else
            $('.logistics').prop('checked', false);
    });
});
</script>
@stop