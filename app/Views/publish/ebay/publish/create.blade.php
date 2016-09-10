<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-05
 * Time: 14:54
 */
?>
<style>
    .row-border {
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 3px 4px 3px rgba(238, 238, 238, 1);
        margin-bottom: 10px;
    }

    .proh {
        width: 100%;
        height: 30px;
    }

    .hideaccordion, .showaccordion {
        float: left;
        height: 18px;
        line-height: 18px;
        position: relative;
        padding: 6px;
    }

    .hideaccordion h1, .showaccordion h1 {
        font-size: 14px;
        font-weight: bold;
        color: #444;
    }

    .hideaccordion h1 i {
        cursor: pointer;
    }

    .probody {
        width: 90%;
        height: 90%;
        padding: 10px;
    }

    .pic-main, .pic-detail, .relate-list {
        padding: 5px;
        border: 1px solid #ccc;
    }

    .pic-main li, .pic-detail li, .relate-list li {
        margin: 5px;
        padding: 0px;
        border: 0px;
        width: 102px;
        text-align: right;
    }

    #Validform_msg {
        color: #7d8289;
        font: 12px/1.5 tahoma, arial, \5b8b\4f53, sans-serif;
        width: 280px;
        background: #fff;
        position: absolute;
        top: 0px;
        right: 50px;
        z-index: 99999;
        display: none;
        filter: progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#999999');
        -webkit-box-shadow: 2px 2px 3px #aaa;
        -moz-box-shadow: 2px 2px 3px #aaa;
    }

    #Validform_msg .iframe {
        position: absolute;
        left: 0px;
        top: -1px;
        z-index: -1;
    }

    #Validform_msg .Validform_title {
        line-height: 25px;
        height: 25px;
        text-align: left;
        font-weight: bold;
        padding: 0 8px;
        color: #fff;
        position: relative;
        background-color: #000;
    }

    #Validform_msg a.Validform_close:link, #Validform_msg a.Validform_close:visited {
        line-height: 22px;
        position: absolute;
        right: 8px;
        top: 0px;
        color: #fff;
        text-decoration: none;
    }

    #Validform_msg a.Validform_close:hover {
        color: #cc0;
    }

    #Validform_msg .Validform_info {
        padding: 8px;
        border: 1px solid #000;
        border-top: none;
        text-align: left;
    }

    /***拖拽样式***/
    .pic-main li div, .pic-detail li div, .relate-list li div {
        width: 102px;
        height: 125px;
        border: 1px solid #fff;
    }

    .pic-main .placeHolder div, .pic-detail .placeHolder div, .relate-list .placeHolder div {
        width: 102px;
        height: 125px;
        background-color: white !important;
        border: dashed 1px gray !important;
    }


</style>

@extends('common.form')
@section('formAction') {{ route('ebayPublish.store') }} @stop
@section('formAttributes') {{ "class=validate_form" }} @stop
@section('formBody')


    <div class="modal fade " id="withdraw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">分类选择</h4>
                </div>
                <div class="modal-body">
                    <input type="text" value="" id="category_num" class="hidden">

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <input type="text" class="form-control " placeholder="输入关键字搜索">
                        </div>
                        <div class="form-group col-sm-1">
                            <label> <a class="btn btn-primary">推荐类目</a></label>
                        </div>
                        <div class="form-group col-sm-1">
                            <label> <a class="btn btn-primary">本地选择</a></label>
                        </div>
                    </div>
                    <div class="row" id="category-set">
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="text" class="form-control" readonly="readonly" placeholder="已选分类"
                                   id="hasChoose">
                            <input type="text" value="" id="category_id" class="hidden">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary disabled" data-dismiss="modal" id="category_btn">提交</a>
                    <a class="btn btn-default" data-dismiss="modal">取消</a>
                </div>
            </div>
        </div>
    </div>
    {{--账号--}}
    <div class="panel panel-default">
        <div class="panel-heading">账号选择</div>
        <div class="panel-body">
            <?php
            $account = array('A-FM', 'A-AN', 'A-ME', 'A-SM', 'D-SM', 'H-XH', 'H-LE', 'H-RE', 'A-OY', 'H-TE', 'J-RY', 'I-LT', 'J-MT', 'J-M5', 'M-SP', '226win', '62gbs');
            foreach ($account as $key => $a):?>
            <div class="col-lg-2">
                <input type="checkbox" value="<?php echo $key;?>"
                       name="choose_account[]" <?php  if (isset($account_id) && $key == $account_id) {
                    echo 'checked="checked"';
                } ?>
                       datatype="*" nullmsg="账号不能为空" class="choose_account "/> <?php echo $a;?>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    {{--站点 分类 sku--}}
    <div class="panel panel-default">
        <div class="panel-heading">站点与分类、SKU</div>
        <div class="panel-body">

            <div class="row">
                <div class="form-group col-sm-1">
                    <label class="right">站点：</label>
                </div>

                <div class="form-group col-sm-1">
                    <select class='form-control select_select0 col-lg-2' name="site" id="site">
                        <option value="">站点</option>
                        @foreach($site as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>

                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label class="right">Ebay_sku：</label>
                </div>

                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" name="ebay_sku" id="ebay_sku" placeholder="ebay_sku"/>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">第一分类分类：</label>
                </div>

                <div class="form-group col-sm-1">
                    <input type="text" class="form-control " placeholder="输入分类ID" name="primary_category"
                           id="primary_category">
                </div>
                <div class="form-group col-sm-1">
                    <label><a class="btn btn-primary category-choose" data-target="#withdraw"
                              data-content="primary_category">选择分类</a></label>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-8">
                    <label id="primary_category_text"></label>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">第二分类分类：</label>
                </div>
                <div class="form-group col-sm-1">
                    <input type="text" class="form-control " placeholder="输入分类ID" name="secondary_category"
                           id="secondary_category">
                </div>
                <div class="form-group col-sm-1">
                    <label><a class="btn btn-primary category-choose" data-target="#withdraw"
                              data-content="secondary_category">选择分类</a></label>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-8">
                    <label id="secondary_category_text"></label>
                </div>
                <br>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">标题</div>
        <div class="panel-body" id="account_tittle">


        </div>
    </div>


    {{--刊登类型与天数--}}
    <div class="panel panel-default">
        <div class="panel-heading">刊登类型与天数</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">刊登类型：</label>
                </div>
                <div class="form-group col-sm-6">
                    <input type="radio" name="listing_type" value="1" class="listing-type" data-content="listing_type1">拍卖
                    <input type="radio" name="listing_type" value="2" class="listing-type" data-content="listing_type2">固定
                    <input type="radio" name="listing_type" value="3" class="listing-type" data-content="listing_type3">多属性
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">刊登天数：</label>
                </div>
                <div class="form-group col-sm-1">
                    <?php
                    $listing_duration = array(
                            'GTC' => 'GTC',
                            'Days_30' => 'Days_30',
                            'Days_15' => 'Days_15',
                            'Days_10' => 'Days_10',
                            'Days_7' => 'Days_7',
                            'Days_3' => 'Days_3',
                            'Days_1' => 'Days_1'
                    );
                    ?>
                    <select class='form-control select_select0 ' name="listing_duration" id="listing_duration">
                        @foreach($listing_duration as $key => $value)
                            <option value="{{$value}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">物品状况与属性</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品状况：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control select_select0 col-sm-1" name="condition_id" id="condition_id">

                    </select>
                </div>
            </div>

            <div class="row hidden" id="condition_description">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品状况描述：</label>
                </div>
                <div class="form-group col-sm-5">
                    <textarea name="condition_description" class="form-control"></textarea>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品属性：</label>
                </div>
                <div class=" col-sm-3">
                    <a class="btn btn-primary btn-sm dir_add" href="javascript: void(0);"
                       onclick="addUserSpecifics()">添加属性</a>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class=""></label>
                </div>
                <div class="form-group col-sm-11" id="addSpecifics">

                </div>

            </div>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">SKU信息</div>
        <div class="panel-body">

            <div id="listing_type1" class="hidden">
                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">私人拍卖：</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <input type="checkbox">不向公众显示买家的名称
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">起拍价格:</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input class="form-control" type="text" name="start_price">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">数量:</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input class="form-control" type="text" name="quantity">
                    </div>
                </div>
            </div>

            <div id="listing_type2" class="hidden">
                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">价格:</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input class="form-control" type="text" name="start_price">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">数量:</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input class="form-control" type="text" name="quantity">
                    </div>
                </div>

            </div>

            <div id="listing_type3" class="hidden">

                <div id="variation_sku">
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label class="text-center">SKU</label>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="text-center">价格</label>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="text-center">数量</label>
                        </div>

                        <div class="form-group col-sm-1">
                            <input type="text" value="" name="variation[]"
                                   class="form-control text-center variation_picture_main">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text" value="" name="variation[]" class="form-control text-center ">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text" value="" name="variation[]" class="form-control text-center">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text" value="UPC" name="variation[]" class="form-control text-center">
                        </div>
                    </div>




                    {{--  <div class="row">
                          <div class="form-group col-sm-1 ">
                              <input type="text" value=""  name="sku[]"  class="form-control text-center " >
                          </div>
                          <div class="form-group col-sm-1 do-change">
                              <input type="text" value="" name="start_price[]" class="form-control text-center sku-price" style="background-color:#fa3658">
                          </div>
                          <div class="form-group col-sm-1 do-change">
                              <input type="text" value=""  name="quantity[]" class="form-control text-center sku-quantity " style="background-color:#fa3658">
                          </div>
                          <div class="form-group col-sm-1">
                              <input type="text" value=""  name="variation0[]" class="form-control text-center variation_picture">
                          </div>
                          <div class="form-group col-sm-1">
                              <input type="text" value=""  name="variation1[]" class="form-control text-center">
                          </div>
                          <div class="form-group col-sm-1">
                              <input type="text" value="" name="variation2[]" class="form-control text-center">
                          </div>
                          <div class="form-group col-sm-1">
                              <input type="text" value="" name="variation3[]" class="form-control text-center">
                          </div>
                      </div>--}}


                </div>
                <div class="panel-footer">
                    <div class="create" id="addItem" onclick="addItem('')"><i
                                class="glyphicon glyphicon-plus red"></i><strong>新增产品</strong></div>
                </div>
                <div id="variation_picture">


                </div>

            </div>


        </div>

    </div>


    <div class="panel panel-default">
        <div class="panel-heading">图片信息</div>
        <div class="panel-body">

            <div class="row form-group">
                <label class="col-sm-2 control-label">橱窗图片：</label>

                <div class="col-sm-10">
                    <div id="ebay_picture">
                        <!-- <a href="javascript:void(0);" class="btn btn-default btn-sm from_local" lang="detail">从我的电脑选取</a> -->
                        <a href="javascript:void(0);" class="btn btn-success btn-sm"
                           onclick="add_pic_in_detail('add','1')">图片外链</a>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm"
                           onclick="getSkuPicture()">获取SKU图片</a>
                        {{-- <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">图片目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">实拍目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">WISH目录上传</a>
                         <a class="btn btn-success btn-sm dir_add" href="javascript: void(0);">无水印目录上传</a>--}}
                        &nbsp;&nbsp;
                        <a class="btn btn-danger btn-xs  pic-del-all"><span
                                    class="glyphicon glyphicon-trash"></span>全部删除</a>
                        <b class="ajax-loading hide">图片上传中...</b>
                    </div>
                    <ul class="list-inline pic-detail">


                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">广告描述</div>
        <div class="panel-body">


            <div class="row form-group">
                <label class="col-sm-2 control-label">描述图片：</label>

                <div class="col-sm-10">
                    <div id="description_picture">
                        <!-- <a href="javascript:void(0);" class="btn btn-default btn-sm from_local" lang="detail">从我的电脑选取</a> -->
                        <a href="javascript:void(0);" class="btn btn-success btn-sm"
                           onclick="add_pic_in_description('add','1')">图片外链</a>
                        <a class="btn btn-danger btn-xs  pic-del-all"><span
                                    class="glyphicon glyphicon-trash"></span>全部删除</a>
                        <b class="ajax-loading hide">图片上传中...</b>
                    </div>
                    <ul class="list-inline pic-detail">

                    </ul>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 control-label">描述详情：</label>

                <div class="col-sm-9">
                    <textarea id="description" name="description">

                    </textarea>

                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">退货政策</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货政策：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0">
                        <option value="ReturnsAccepted">ReturnsAccepted</option>
                        <option value="ReturnsNotAccepted">ReturnsNotAccepted</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货天数：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" id="returns_with_in" name="returns_with_in">
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" value="">提供节假日延期退货至12月31日
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退款方式：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" id="refund" name="refund">
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货运费由谁负担：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" name="shipping_costpaid_by" id="shipping_costpaid_by" >
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货政策详情：</label>
                </div>
                <div class="form-group col-sm-4">
                    <textarea class="form-control"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">买家要求</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" name="no_paypal"> 没有PayPal用户
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" name="no_ship"> 主要运送地址在我的运送范围之外
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox"> 曾收到
                    <select class="select_select0 col-sm-1" name="unpaid">
                        @foreach(config('ebaysite.unpaid')as $key=>$value)
                            <option value="{{$value}}">{{$value}}</option>
                        @endforeach
                    </select>
                    个弃标个案，在过去
                    <select class="select_select0 col-sm-2" name="unpaid_day">
                        @foreach(config('ebaysite.unpaid_day')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox"> 曾收到
                    <select class="select_select0 col-sm-1" name="policy">
                        @foreach(config('ebaysite.policy')as $key=>$value)
                            <option value="{{$value}}">{{$value}}</option>
                        @endforeach
                    </select>
                    个违反政策检举，在过去
                    <select class="select_select0 col-sm-2" name="policy_day">
                        @foreach(config('ebaysite.policy_day')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox">信用指标等于或低于：
                    <select class="select_select0 col-sm-1" name="feedback">
                        @foreach(config('ebaysite.feedback')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-6">
                    <input type="checkbox">在过去10天内曾出价或购买我的物品，已达到我所设定的限制
                    <select class="select_select0 col-sm-1" name="item_count">
                        @foreach(config('ebaysite.item_count')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    这项限制只适用于买家信用指数等于或低于
                    <select class="select_select0 col-sm-1" name="item_count_feedback">
                        @foreach(config('ebaysite.item_count_feedback')as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">物品所在地</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品所在地：</label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" name="location" id="location">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">国家或地区：</label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" name="country">
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">邮编：</label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" name="postal_code" id="postal_code">
                </div>
            </div>

        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">国内运输</div>
        <div class="panel-body">
            <?php
            $ship_name = [
                    1 => '第一运输',
                    2 => '第二运输'
            ]

            ?>
            @for($i=1;$i<3;$i++)
                <div id="{{'shipping'.$i}}">
                    <div class="row">
                        <div class="form-group col-sm-1">
                        </div>
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">{{$ship_name[$i]}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输方式：</label>
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control select_select0 col-sm-1 shipping" name="shipping[{{$i}}][ShippingService]">

                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运费：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" name="shipping[{{$i}}][ShippingServiceCost]">

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">额外每件加收：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" name="shipping[{{$i}}][ShippingServiceAdditionalCost]">
                        </div>
                    </div>

                </div>
            @endfor
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">国际运输</div>
        <div class="panel-body">
            @for($i=1;$i<3;$i++)
                <div id="{{'international'.$i}}">
                    <div class="row">
                        <div class="form-group col-sm-1">
                        </div>
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">{{$ship_name[$i]}}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输方式：</label>
                        </div>
                        <div class="form-group col-sm-3">
                            <select class="form-control select_select0 col-sm-1 international" name="InternationalShipping[{{$i}}][ShippingService]">

                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运费：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" name="InternationalShipping[{{$i}}][ShippingServiceCost]">

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">额外每件加收：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control" type="text" name="InternationalShipping[{{$i}}][ShippingServiceAdditionalCost]">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输国家：</label>
                        </div>
                        <div class="form-group col-sm-4">
                            <input class="form-control" type="text" name="InternationalShipping[{{$i}}][ShipToLocation]">
                        </div>
                    </div>

                </div>
            @endfor
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">不运输国家</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">不运输国家：</label>
                </div>
                <div class="form-group col-sm-8">
                    <input class="form-control" type="text" name="un_ship" id="un_ship">
                </div>
            </div>

        </div>
    </div>
    <input type="hidden" name="action" id="action" value=""/>
    <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" id="id"/>

@stop

@section('formButton')
    <div class="text-center">
        <button type="submit" name="save" class="btn btn-success submit_btn ">保存为草稿</button>
        <button type="submit" name="editAndPost" class="btn btn-primary  submit_btn ">保存并且发布</button>
    </div>

@show{{-- 表单按钮 --}}

@section('pageJs')
    <script src="{{ asset('plugins/ueditor/umeditor.config.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/umeditor.min.js') }}"></script>
    <script src="{{ asset('plugins/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <link href="{{ asset('plugins/ueditor/themes/default/css/umeditor.css') }}" rel="stylesheet">
    <script type="text/javascript">
        var content = UM.getEditor('description', {
            initialFrameHeight: 500
        });
        content.setWidth("100%");
        $(".edui-body-container").css("width", "80%");
        $('.select_select0').select2();

        $('.select_select_tags').select2({
            tags: true
        });

        $(".pic-detail").dragsort({
            dragSelector: "div",      //容器拖动手柄
            dragBetween: true,                   //
            dragEnd: function () {
            },                   //执行之后的回调函数
            placeHolderTemplate: "<li class='placeHolder'><div></div></li>"     //拖动列表的HTML部分
        });




        $('.submit_btn').click(function () {
            var name = $(this).attr('name');
            $("#action").val(name);

        });
        $('.validate_form').Validform({
            btnSubmit: '.submit_btn',
            btnReset: '.btn-reset',
            ignoreHidden: true,
            ajaxPost: true,
            callback: function (data) { //返回数据
                if (data.status) {
                    if (data.data) {
                        $('#id').val(data.data);
                    }
                }
            }
        });


        $(document).on('click', '.pic-del-all', function () {
            if (confirm('确认删除全部图片吗？')) {
                $(this).closest('.form-group').find('ul').empty();
            }
        });

        //删除主图片
        $(document).on('click', '.pic-del', function () {
            //event.preventDefault();
            $(this).closest('li').remove();
        });
        $(document).on('click', '.bt-right', function () {
            // $(this).parent().next().remove();
            $(this).parent().remove();

        });
        $(document).on('click', '.show-sub', function () {

            if ($(this).parent().parent().next().hasClass('hidden')) {
                $(this).parent().parent().next().removeClass('hidden');
            } else {
                $(this).parent().parent().next().addClass('hidden');
            }

        });


        $('.category-choose').click(function () {
            if ($('#site').val() == '') {
                alert('先选择站点');
                return false;
            }
            $("#category_num").val($(this).attr('data-content'));
            initCategory(0, 0);
            $('#withdraw').modal('show')
        });

        $("#site").change(function () {
            initSite();
        });
        $(document).on('change', '.category_list', function () {
            var a_category_id = $(this).val();
            var a_isleaf = $(this).find('option:selected').attr('lang');
            var level = $(this).find('option:selected').attr('data-content');
            $(this).parents('.col-lg-3').nextAll().remove();
            if (a_isleaf != 'true') {
                initCategory(a_category_id, level);
                if (!$('#category_btn').hasClass('disabled')) {
                    $('#category_btn').addClass('disabled');
                }
            } else {
                if ($('#category_btn').hasClass('disabled')) {
                    $('#category_btn').removeClass('disabled');
                }

                var category_name = $("#category-set").children().map(function () {
                    return $(this).find('option:selected').text();
                }).get().join('>>');
                $("#hasChoose").val(category_name);
                $("#category_id").val(a_category_id);
            }
        });
        $('#category_btn').click(function () {
            var category_num = $('#category_num').val();
            var category_id = $('#category_id').val();
            var hasChoose = $('#hasChoose').val();
            $('#' + category_num).val(category_id);
            var hasChooseText = category_num + '_text';
            $('#' + hasChooseText).text(hasChoose);
            if (category_num == 'primary_category') {
                addSpecifics();
                addCondition();
            }

        });
        $(".listing-type").click(function () {
            var check_value = $(this).val();
            var value = $(this).attr('data-content');
            if (!$("#" + value).hasClass("has-add")) {
                $.ajax({
                    url: "{{ route('ebayPublish.ajaxInitErpData') }}",
                    data: {
                        sku: $("#ebay_sku").val(),
                        type: 'sku+picture'
                    },
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        if (result) {
                            $("#ebay_picture").next().empty();
                            for (var i = 0; i < result.picture.length; i++) {
                                add_pic_in_detail('auto', result.picture[i]);
                            }
                            if (check_value == 3) {
                                for (var i = 0; i < result.sku.length; i++) {
                                    addItem(result.sku[i]);
                                }
                            }
                        }
                    }
                });
            }
            $("#" + value).removeClass("hidden").addClass("has-add");
            $(".listing-type").each(function () {
                if ($(this).attr('data-content') != value) {
                    if (!$('#' + $(this).attr('data-content')).hasClass('hidden')) {
                        $('#' + $(this).attr('data-content')).addClass('hidden');
                    }
                }
            });
        });
        $("#condition_id").change(function () {
            var value = $("#condition_id").val();
            if (value != 1000) {
                if ($('#condition_description').hasClass("hidden")) {
                    $('#condition_description').removeClass("hidden");
                }
            } else {
                if (!$('#condition_description').hasClass("hidden")) {
                    $('#condition_description').addClass("hidden");
                }
            }

        });

        $(".choose_account").click(function () {               //checkBox点击事件
            var value = $(this).val();
            var text_name = $(this).parent().text();
            var id_name = 'account_tittle_' + value;
            if ($(this).is(':checked')) { // 选中

                var add_tittle = '<div id="' + id_name + '"><div class="row"><div class="form-group col-sm-1"><label for="subject" class="right">' + text_name + '：</label></div>' +
                        '<div class="form-group col-sm-8">' +
                        '<input class="form-control" type="text" placeholder="标题" name="title[' + value + ']"></div>' +
                        '<div class="form-group col-sm-1">' +
                        '<button type="button" class="btn btn-success show-sub"  title="填写子标题"><i class="glyphicon glyphicon-plus"></i></button></div></div>' +
                        '<div class="row hidden">' +
                        '<div class="form-group col-sm-1">' +
                        '<label for="subject" class="right"></label>' +
                        '</div>' +
                        '<div class="form-group col-sm-6" name="sub_title[' + value + ']">' +
                        '<input class="form-control" type="text" placeholder="子标题">' +
                        '</div></div></div>';
                $('#account_tittle').append(add_tittle);
            } else { //取消
                $("#account_tittle_" + value).remove();

            }
        });


        function addItem(sku) {
            var html = '<div class="row">' +
                    '<div class="form-group col-sm-1 ">' +
                    '<input type="text" value="' + sku + '"  name="sku[]"  class="form-control text-center " >' +
                    '</div>' +
                    '<div class="form-group col-sm-1 do-change">' +
                    '<input type="text" value="" name="start_price[]" class="form-control text-center sku-price"  onblur="batchUpdatePrice(this)" style="background-color:#fa3658"></div>' +
                    '<div class="form-group col-sm-1 do-change">' +
                    '<input type="text" value=""  name="quantity[]" class="form-control text-center sku-quantity " onblur="batchUpdateQuantity(this)" style="background-color:#fa3658"></div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" value=""  name="variation0[]" class="form-control text-center variation_picture" onblur="variationPicture(this)"></div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" value=""  name="variation1[]" class="form-control text-center"></div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" value="" name="variation2[]" class="form-control text-center"></div>' +
                    '<div class="form-group col-sm-1">' +
                    '<input type="text" value="Does not apply" name="variation3[]" class="form-control text-center"></div>' +
                    '<button type="button" class="btn btn-danger bt-right" title="删除该SKU"><i class="glyphicon glyphicon-trash"></i></button></div>';
            $("#variation_sku").append(html);
        }
        function initSite() {
            var site = $("#site").val();
            if (site == '') {
                return false;
            }
            $.ajax({
                url: "{{ route('ebayPublish.ajaxInitSite') }}",
                data: {
                    site: site
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if (result) {
                        $('.shipping').select2({
                            placeholder: "Select a shipping",
                            allowClear: true
                        }).empty().append(result.ship_text);
                        $('.international').select2({
                            placeholder: "Select a international shipping",
                            allowClear: true
                        }).empty().append(result.international_text);

                        $("#returns_with_in").empty().append(result.returns_with_in);
                        $("#shipping_costpaid_by").empty().append(result.shipping_costpaid_by);
                        $("#refund").empty().append(result.refund);
                    }

                }
            });

        }
        function initCategory(category_parent_id, level) {
            var site = $("#site").val();
            $.ajax({
                url: "{{ route('ebayPublish.ajaxInitCategory') }}",
                data: {
                    site: site,
                    category_parent_id: category_parent_id,
                    level: level
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    var html = '<div class="form-group text-left col-lg-3"><select  size="12" class="form-control category_list" multiple> ';
                    $.each(result, function (index, el) {
                        html += '<option value="' + el.category_id + '" lang="' + el.leaf_category + '" data-content="' + el.category_level + '">' + el.category_name + '</option>';
                    });
                    html = html + ' </select></div>';
                    if (level == 0) {
                        $('#category-set').empty();
                    }
                    $('#category-set').append(html);
                }
            });
        }
        function addSpecifics() {
            var category_id = $("#primary_category").val();
            var site = $("#site").val();
            $.ajax({
                url: "{{ route('ebayPublish.ajaxInitSpecifics') }}",
                data: {
                    site: site,
                    category_id: category_id
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if (result) {
                        $("#addSpecifics").empty();
                        for (var i = 0; i < result.length; i++) {
                            if (result[i].must) {
                                var html = '<div class=" col-sm-6"><label class=" text-right col-sm-3">*';
                            } else {
                                var html = '<div class=" col-sm-6"><label class=" text-right col-sm-3">';
                            }
                            html = html + result[i].name + ':</label><select name="item_specifics[' + result[i].name + ']" class="select_select_tags col-sm-3" >' + result[i].text + '</select></div>';
                            $("#addSpecifics").append(html);
                            $('.select_select_tags').select2({
                                tags: true
                            });
                        }
                    }
                }
            });
        }
        function addCondition() {
            var category_id = $("#primary_category").val();
            var site = $("#site").val();
            $.ajax({
                url: "{{ route('ebayPublish.ajaxInitCondition') }}",
                data: {
                    site: site,
                    category_id: category_id
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if (result) {
                        $("#condition_id").select2({
                            allowClear: true
                        }).empty().append(result.text);
                    }
                }
            });
        }
        function addUserSpecifics() {
            var str = prompt("新增属性名称");
            if (str) {
                var html = '<div class=" col-sm-6"><label onclick="deleteSpecifics(this)"class=" text-right col-sm-3">' + str + ':</label><select class="select_select_tags col-sm-3"   name="item_specifics[' + str + ']"></select></div>';
                $("#addSpecifics").append(html);
                $('.select_select_tags').select2({
                    tags: true
                });
            }
        }
        function deleteSpecifics(e) {
            $(e).parent().remove();
        }
        function setMulSkuPicture(e) {
            var str = prompt("请输入图片外链");
            if (str) {
                $(e).parent().next().children().eq(1).remove();
                var html = '<img width="100px" height="100px" src="' + str + '" onclick="deleteMulPicture(this)">';
                $(e).parent().next().children().eq(0).val(str);
                $(e).parent().next().append(html)

            }
        }
        function deleteMulPicture(e) {
            if (confirm('确定要删除图片吗？')) {
                $(e).parent().children().eq(0).val('');
                $(e).remove();
            }
        }
        function batchUpdateQuantity(e) {
            var value = $(e).val();//background-color   #25fa69
            $(e).css("background-color", '#25fa69');
            $(".sku-quantity").each(function () {
                if ($(this).css("background-color") == 'rgb(250, 54, 88)') {
                    $(this).val(value);
                }
            });

        }
        function batchUpdatePrice(e) {
            var value = $(e).val();//background-color   #25fa69
            $(e).css("background-color", '#25fa69');
            $(".sku-price").each(function () {
                if ($(this).css("background-color") == 'rgb(250, 54, 88)') {
                    $(this).val(value);
                }
            });
        }
        function variationPicture(e) {
            $(".variation_picture").each(function () {
                var value = $(this).val();
                if (value != '') {
                    var is_has = false;
                    $(".is-has-picture").each(function () {
                        if ($(this).attr('data-content') == value) {
                            is_has = true;
                        }
                    });
                    if (!is_has) {
                        var html = ' <div class="row is-has-picture "  data-content="' + value + '"> ' +
                                '<div class="form-group col-sm-2 text-left">' +
                                '<label onclick="setMulSkuPicture(this)">' + value + ':</label>' +
                                '</div>' +
                                '<div class="form-group col-sm-2 ">' +
                                '<input type="text" class="hidden" value="http://etiger.info/upload/E3112/E3112-8.jpg">' +
                                '</div></div>';
                        $("#variation_picture").append(html);
                    }
                }
            });
            $(".is-has-picture").each(function () {
                var mark = $(this);
                var value = $(this).attr('data-content');
                var is_has = false;
                $(".variation_picture").each(function () {
                    if (value == $(this).val()) {
                        is_has = true;
                    }
                });
                if (!is_has) {
                    mark.remove();
                }

            });
        }
        function add_pic_in_detail(type, value) {
            if (type == 'add') {
                var str = prompt("图片外链地址");
                if (!str) {
                    return false;
                }
            }
            if (type == 'auto') {
                var str = value;
            }
            var html = '<li>' +
                    '<div style="cursor: pointer;"><img width="100" height="100" style="border: 0px;" src="' + str + '">' +
                    '<input type="hidden" value="' + str + '" name="picture_details[]">' +
                    '<input type="checkbox" title="可做为橱窗图" name="specify_image[]" value="' + str + '" >' +
                    '<a class="pic-del" href="javascript: void(0);">删除</a>' +
                    '</div>' +
                    '</li>';
            $("#ebay_picture").next().append(html);
        }

        function add_pic_in_description(type, value) {
            if (type == 'add') {
                var str = prompt("图片外链地址");
                if (!str) {
                    return false;
                }
            }
            if (type == 'auto') {
                var str = value;
            }
            var html = '<li>' +
                    '<div style="cursor: pointer;"><img width="100" height="100" style="border: 0px;" src="' + str + '">' +
                    '<input type="hidden" value="' + str + '" name="description_picture[]">' +
                    '<a class="pic-del" href="javascript: void(0);">删除</a>' +
                    '</div>' +
                    '</li>';
            $("#description_picture").next().append(html);
        }

        function getSkuPicture() {

        }

    </script>
@section('childJs')@show
@stop