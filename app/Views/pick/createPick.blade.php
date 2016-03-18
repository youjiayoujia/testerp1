@extends('common.form')
<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
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
            <input type='checkbox' name='channel' class='channel_all'>全部
            @foreach($channels as $channel)
                <input type='checkbox' name='channel[]' class='channel' value={{$channel->id}}>{{$channel->name}}
            @endforeach
            </td>
        </tr>
        <tr>
            <td class='col-lg-1'>包裹类型</td>
            <td class='row'>
            <input type='checkbox' class='package_all'>全部
            <input type='checkbox' name='package[]' class='package' value='SINGLE'>单单
            <input type='checkbox' name='package[]' class='package' value='SINGLEMULTI'>单多
            <input type='checkbox' name='package[]' class='package' value='MULTI'>多多
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
                <td><input type='checkbox' class='logistic_all'>全部</td>
            </tr>
            @foreach($logistics as $logistic)
            <tr>
                <td><input type='checkbox' name='logistic[]' class='logistic' value={{$logistic->id}}>{{$logistic->logistics_type}}</td>
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
<script type='text/javascript'>
$(document).ready(function(){
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

    $('.logistic_all').click(function(){
        if($(this).prop('checked') == true)
            $('.logistic').prop('checked', true);
        else
            $('.logistic').prop('checked', false);
    });
});
</script>
