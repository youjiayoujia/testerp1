<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-24
 * Time: 13:23
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
        color: #7D8289;
        font: 12px/1.5 tahoma, arial, \5b8b\4f53, sans-serif;
        width: 280px;
        background: #fff;
        position: absolute;
        top: 100px;
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
@section('formAction'){{ route('ebayPublish.store') }} @stop
@section('formAttributes') {{ "class=validate_form" }} @stop
@section('formBody')
    <div class="modal fade " id="mulAccount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-left" id="myModalLabel">多账号价格设置<span id="mulAccountName"></span></h4>
                </div>
                <div class="modal-body" id="mulAccountSet">

                </div>
                <div class="modal-footer">
                    <a class="btn btn-default" data-dismiss="modal">取消</a>
                </div>
            </div>
        </div>
    </div>

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
                            <input type="text" id="key_word" class="form-control " placeholder="输入关键字搜索">
                        </div>
                        <div class="form-group col-sm-1">
                            <label> <a class="btn btn-primary" onclick="suggestCategory()">推荐类目</a></label>
                        </div>
                        <div class="form-group col-sm-1">
                            <label> <a class="btn btn-primary" onclick="initCategory(0, 0)">本地选择</a></label>
                        </div>
                    </div>
                    <div class="row" id="category-set">

                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="text" class="form-control" readonly="readonly" placeholder="已选分类"
                                   id="hasChoose">
                            <input type="text" value=""   id="category_id" class="hidden">
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
                            <option value="{{$key}}" {{Tool::isSelected('site', $key,$model) }}>{{$value}}</option>
                        @endforeach
                    </select>

                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">仓库：</label>
                </div>
                <div class="form-group col-sm-6">
                    <select class="select_select0 col-sm-4" name="warehouse" id="warehouse" datatype="*" nullmsg="仓库不能为空">
                        <option value="">==请选择==</option>
                        @foreach(config('ebaysite.warehouse') as $key=>$name)
                            <option value="{{$key}}"   {{  Tool::isSelected('warehouse', $key,$model) }}>{{$name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label class="right">Ebay_sku：</label>
                </div>

                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" name="ebay_sku" id="ebay_sku" placeholder="ebay_sku" value="<?php echo trim(Tool::getNewEbaySku($model->sku)) ?>"/>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">第一分类分类：</label>
                </div>

                <div class="form-group col-sm-1">
                    <input type="text" class="form-control " placeholder="输入分类ID" name="primary_category"
                           id="primary_category" value="{{$model->primary_category}}">
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
                           id="secondary_category" value="{{$model->secondary_category}}">
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

    {{--账号--}}
    <div class="panel panel-default">
        <div class="panel-heading">账号选择</div>
        <div class="panel-body">
            @foreach($account as $key => $a)
            <div class="col-lg-2">
                <input type="checkbox" value="{{$key}}"
                       name="choose_account[]"
                       {{ Tool::isChecked('account_id', $key,$model) }}
                       datatype="*" nullmsg="账号不能为空" class="choose_account "/>{{$a}}
            </div>
             @endforeach
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">标题</div>
        <div class="panel-body" id="account_tittle">
        <div id="{{'account_tittle_'.$model->account_id}}">
            <div class="row"><div class="form-group col-sm-1"><label for="subject" class="right">{{$model->account_id}}：</label></div>
                    <div class="form-group col-sm-8">
                        <input class="form-control" type="text" placeholder="标题" name="title[{{$model->account_id}}]"
                                value="{{$model->title}}"
                                ></div>
                    <div class="form-group col-sm-1">
                        <button type="button" class="btn btn-success show-sub"  title="填写副标题"><i class="glyphicon glyphicon-plus"></i></button></div></div>
                <div class="row  @if(empty($model->sub_title)))) hidden @endif">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right"></label>
                        </div>
                    <div class="form-group col-sm-6" name="sub_title[{{$model->account_id}}]">
                        <input class="form-control" type="text" placeholder="副标题" value="{{$model->sub_title}}">
                        </div></div></div>


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
                    <input type="radio" name="listing_type" value="1" class="listing-type" data-content="listing_type1"  @if($model->listing_type=='Chinese') checked @endif>拍卖
                    <input type="radio" name="listing_type" value="2" class="listing-type" data-content="listing_type2"  @if($model->listing_type=='FixedPriceItem'&&$model->multi_attribute==0) checked @endif>固定
                    <input type="radio" name="listing_type" value="3" class="listing-type" data-content="listing_type3"  @if($model->listing_type=='FixedPriceItem'&&$model->multi_attribute==1) checked @endif>多属性
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
                            <option value="{{$value}}" @if($model->listing_duration==$value) selected="selected" @endif  >{{$value}}</option>
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
                        @if(isset($condition)&&!empty($condition))
                            @foreach($condition as  $con)
                               <option value="{{$con['condition_id']}}" {{Tool::isSelected('condition_id', $con['condition_id'],$model)}}>{{$con['condition_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="row hidden" id="condition_description">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品状况描述：</label>
                </div>
                <div class="form-group col-sm-5">
                    <textarea name="condition_description" class="form-control">
                        {{$model->condition_description}}
                    </textarea>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">物品属性：</label>
                </div>
                <div class=" col-sm-3">
                    <a class="btn btn-primary btn-sm dir_add" href="javascript: void(0);"
                       onclick="addUserSpecifics(1,1)">添加属性</a>
                </div>
            </div>

            <?php
            $specifics =json_decode($model->item_specifics,true);
            ?>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class=""></label>
                </div>
                <div class="form-group col-sm-11" id="addSpecifics">
                    @if(isset($specifics)&&!empty($specifics))
                    @foreach($specifics as $key=> $spe)
                            <div class=" col-sm-6"><label class=" text-right col-sm-3">{{$key}}</label>
                                <select name="item_specifics[{{$key}}]" class="select_select_tags col-sm-3" >
                                    @if(!empty($spe))
                                    <option value="{{$spe}}">{{$spe}}</option>
                                    @endif
                                </select></div>
                    @endforeach
                    @endif
                </div>


            </div>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">SKU信息</div>
        <div class="panel-body">

            <div id="listing_type1" class="@if($model->listing_type=='Chinese')   @else  hidden  @endif">
                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">私人拍卖：</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <input type="checkbox" name="private_listing" value="true">不向公众显示买家的名称
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">起拍价格:</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input class="form-control" type="text" id="start_price1" name="start_price1" value="{{$model->start_price}}" onblur="setData(1)">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">数量:</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input class="form-control" type="text" name="quantity1" value="{{$model->quantity}}">
                    </div>
                </div>
            </div>

            <div id="listing_type2" class=" @if($model->listing_type=='FixedPriceItem'&&$model->multi_attribute==0) @else hidden @endif">
                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">价格:</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input class="form-control" type="text" id="start_price2" name="start_price2" value="{{$model->start_price}}" onblur="setData(2)">
                    </div>
                    <div class="form-group col-sm-2">
                        <button type="button" class="btn btn-info mul-account" title="设置不用账号价格"><i class="glyphicon glyphicon-cog"></i></button>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-1">
                        <label for="subject" class="right">数量:</label>
                    </div>
                    <div class="form-group col-sm-2">
                        <input class="form-control" type="text" name="quantity2" value="{{$model->quantity}}">
                    </div>
                </div>

            </div>

            <div id="listing_type3" class="@if($model->listing_type=='FixedPriceItem'&&$model->multi_attribute==1) @else hidden @endif">
                <?php
                    if(!empty($model->variation_specifics)){
                        $variation_specifics = json_decode($model->variation_specifics,true);
                        $variation=[];
                        $i=1;
                        foreach($variation_specifics as $key=>$value){
                            if($key=='UPC'||$key=='EAN'){
                                $variation[4]=$key;
                            }else{
                                $variation[$i]=$key;
                                $i++;
                            }

                        }
                    }
                ?>
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
                            <input type="text" value="@if(isset($variation[1])){{$variation[1]}}@endif" name="variation[]"
                                   class="form-control text-center variation_picture_main">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text"  value="@if(isset($variation[2])){{$variation[2]}}@endif" name="variation[]" class="form-control text-center ">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text"  value="@if(isset($variation[3])){{$variation[3]}}@endif" name="variation[]" class="form-control text-center">
                        </div>
                        <div class="form-group col-sm-1">
                            <input type="text"  value="@if(isset($variation[4])){{$variation[4]}}@endif" name="variation[]" class="form-control text-center" datatype="*" nullmsg="UPC或EAN不能为空">
                        </div>
                    </div>
                    @if($model->listing_type=='FixedPriceItem'&&$model->multi_attribute==1)
                    @foreach($model->details as $key=> $sku)
                    <div class="row">
                        <div class="form-group col-sm-1 "><input type="text" value="{{$sku->sku}}"  name="sku[]"  class="form-control text-center  sku-sku" ></div>
                        <div class="form-group col-sm-1 do-change">
                            <input type="text" value="{{$sku->start_price}}" name="start_price[]"  class="form-control text-center sku-price"  onblur="batchUpdatePrice(this)" style="background-color:#fa3658"></div>
                        <div class="form-group col-sm-1 do-change">
                            <input type="text" value="{{$sku->quantity}}"  name="quantity[]" class="form-control text-center sku-quantity " onblur="batchUpdateQuantity(this)" style="background-color:#fa3658"></div>
                        <div class="form-group col-sm-1">
                            <input type="text" value="@if(isset($variation[1])){{$variation_specifics[$variation[1]][$key]}}@endif"  name="variation0[]" class="form-control text-center variation_picture" onblur="variationPicture(this)"></div>
                        <div class="form-group col-sm-1">
                            <input type="text" value="@if(isset($variation[2])){{$variation_specifics[$variation[2]][$key]}}@endif"  name="variation1[]" class="form-control text-center"></div>
                        <div class="form-group col-sm-1">
                            <input type="text" value="@if(isset($variation[3])){{$variation_specifics[$variation[3]][$key]}}@endif" name="variation2[]" class="form-control text-center"></div>
                        <div class="form-group col-sm-1">
                            <input type="text" value="@if(isset($variation[4])){{$variation_specifics[$variation[4]][$key]}}@endif" name="variation3[]" class="form-control text-center"></div>
                        <button type="button" class="btn btn-danger bt-right" title="删除该SKU"><i class="glyphicon glyphicon-trash"></i></button>
                        <button type="button" class="btn btn-info mul-account " title="设置不同账号价格"><i class="glyphicon glyphicon-cog"></i></button></div>
                        @endforeach
                        @endif
                </div>
                <div class="panel-footer">
                    <div class="create" id="addItem" onclick="addItem('')"><i
                                class="glyphicon glyphicon-plus red"></i><strong>新增产品</strong></div>
                </div>
                <div id="variation_picture">
                    <?php  $variation_picture = json_decode($model->variation_picture,true); ?>
                    @if(!empty($variation_picture))
                        @foreach($variation_picture as $key=>$value)
                                @foreach($value as $k_v=>$v_k)
                                <div class="row is-has-picture "  data-content="{{$k_v}}">
                                    <div class="form-group col-sm-2 text-left">
                                        <label onclick="setMulSkuPicture(this)">{{$k_v}}:</label>
                                        </div>
                                    <div class="form-group col-sm-2 ">
                                        <img width="100px" height="100px" src="{{$v_k}}" onclick="deleteMulPicture(this)">
                                        <input type="text" class="hidden" name="variation_picture[{{$k_v}}]" value="{{$v_k}}">
                                        </div></div>
                                @endforeach
                        @endforeach
                    @endif


                </div>

            </div>


        </div>

    </div>

    <?php

        $picture_details = json_decode($model->picture_details,true);
    ?>
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
                        @if(!empty($picture_details))
                            @foreach($picture_details as $pic)
                        <li>
                            <div style="cursor: pointer;"><img width="100" height="100" style="border: 0px;" src="{{$pic}}">
                                <input type="hidden" value="{{$pic}}" name="picture_details[]">
                                <input type="checkbox" title="可做为橱窗图" name="specify_image[]" value=" {{$pic}}" >
                                <a class="pic-del" href="javascript: void(0);">删除</a>
                                </div>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php

    $description_picture = json_decode($model->description_picture,true);
    ?>

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
                        @if(!empty($description_picture))
                            @foreach($description_picture as $pic)
                                <li>
                                    <div style="cursor: pointer;"><img width="100" height="100" style="border: 0px;" src="{{$pic}}">
                                        <input type="hidden" value="{{$pic}}" name="description_picture[]">
                                        <a class="pic-del" href="javascript: void(0);">删除</a>
                                        </div>
                                    </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2">
                    <label for="subject" class="right">描述模板：</label>
                    {{-- <a href="javascript:void(0);" class="btn btn-success btn-sm"
                        onclick="previewDescription()">预览</a>--}}
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" name="description_id" >
                        <option value="">==请选择==</option>
                        @foreach($description as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 control-label">描述详情：</label>

                <div class="col-sm-9">
                    <textarea id="description" name="description">
                        {{htmlspecialchars_decode($model->description)}}
                    </textarea>

                </div>

            </div>
        </div>
    </div>

    <?php
        $returns_with_in = json_decode($siteInfo->returns_with_in,true);
        $shipping_costpaid_by = json_decode($siteInfo->shipping_costpaid_by,true);
        $refund = json_decode($siteInfo->refund,true);
        $return_policy = json_decode($model->return_policy, true);
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">退货政策</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货政策：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" name="returns_option">
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
                        @if(!empty($returns_with_in)))
                        @foreach($returns_with_in as $value)
                            <option value="{{$value}}" @if($return_policy['ReturnsWithinOption']==$value){{'selected="selected"'}}@endif>{{$value}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" value="true" name="extended_holiday" @if($return_policy['ExtendedHolidayReturns']){{'checked'}}@endif>提供节假日延期退货至12月31日
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退款方式：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" id="refund" name="refund">
                        @if(!empty($refund)))
                        @foreach($refund as $value)
                            <option value="{{$value}}" @if($return_policy['ReturnsWithinOption']==$value){{'selected="selected"'}}@endif>{{$value}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货运费由谁负担：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control col-sm-1 select_select0" name="shipping_costpaid_by"
                            id="shipping_costpaid_by">
                        @if(!empty($shipping_costpaid_by)))
                        @foreach($shipping_costpaid_by as $value)
                            <option value="{{$value}}" @if($return_policy['ReturnsWithinOption']==$value){{'selected="selected"'}}@endif >{{$value}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">退货政策详情：</label>
                </div>
                <div class="form-group col-sm-4">
                    <textarea class="form-control" name="refund_description"> {{$return_policy['Description']}}</textarea>
                </div>
            </div>
        </div>
    </div>

    <?php
    $buyer_requirement = json_decode($model->buyer_requirement, true);
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">买家要求</div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" name="no_paypal" value="true"  @if($buyer_requirement['LinkedPayPalAccount']){{'checked'}}@endif> 没有PayPal用户
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="checkbox" name="no_ship" value="true" @if($buyer_requirement['ShipToRegistrationCountry']){{'checked'}}@endif >  主要运送地址在我的运送范围之外
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox" name="unpaid_on" value="true" @if($buyer_requirement['unpaid_on']){{'checked'}}@endif > 曾收到
                    <select class="select_select0 col-sm-1" name="unpaid">
                        @foreach(config('ebaysite.unpaid')as $key=>$value)
                            <option value="{{$value}}" @if($value==$buyer_requirement['MaximumUnpaidItemStrikesInfo']['Count']){{'selected="selected"'}}@endif >{{$value}}</option>
                        @endforeach
                    </select>
                    个弃标个案，在过去
                    <select class="select_select0 col-sm-2" name="unpaid_day">
                        @foreach(config('ebaysite.unpaid_day')as $key=>$value)
                            <option value="{{$key}}" @if($key==$buyer_requirement['MaximumUnpaidItemStrikesInfo']['Period']){{'selected="selected"'}}@endif >{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox" name="policy_on" value="true" @if($buyer_requirement['policy_on']){{'checked'}}@endif  > 曾收到
                    <select class="select_select0 col-sm-1" name="policy">
                        @foreach(config('ebaysite.policy')as $key=>$value)
                            <option value="{{$value}}" @if($value==$buyer_requirement['MaximumBuyerPolicyViolations']['Count']){{'selected="selected"'}}@endif  >{{$value}}</option>
                        @endforeach
                    </select>
                    个违反政策检举，在过去
                    <select class="select_select0 col-sm-2" name="policy_day">
                        @foreach(config('ebaysite.policy_day')as $key=>$value)
                            <option value="{{$key}}" @if($key==$buyer_requirement['MaximumBuyerPolicyViolations']['Period']){{'selected="selected"'}}@endif >{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="checkbox" name="feedback_on" value="true" @if($buyer_requirement['feedback_on']){{'checked'}}@endif  >信用指标等于或低于：
                    <select class="select_select0 col-sm-1" name="feedback">
                        @foreach(config('ebaysite.feedback')as $key=>$value)
                            <option value="{{$key}}"  @if($key==$buyer_requirement['MinimumFeedbackScore']){{'selected="selected"'}}@endif >{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right"></label>
                </div>
                <div class="form-group col-sm-6">
                    <input type="checkbox" name="item_count_on" value="true" @if($buyer_requirement['item_count_on']){{'checked'}}@endif >在过去10天内曾出价或购买我的物品，已达到我所设定的限制
                    <select class="select_select0 col-sm-1" name="item_count">
                        @foreach(config('ebaysite.item_count')as $key=>$value)
                            <option value="{{$key}}" @if($key==$buyer_requirement['MaximumItemRequirements']['MaximumItemCount']){{'selected="selected"'}}@endif  >{{$value}}</option>
                        @endforeach
                    </select>
                    这项限制只适用于买家信用指数等于或低于
                    <select class="select_select0 col-sm-1" name="item_count_feedback">
                        @foreach(config('ebaysite.item_count_feedback')as $key=>$value)
                            <option value="{{$key}}" @if($key==$buyer_requirement['MaximumItemRequirements']['MinimumFeedbackScore']){{'selected="selected"'}}@endif >{{$value}}</option>
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
                    <input type="text" class="form-control" name="location" id="location" value="{{$model->location}}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">国家或地区：</label>
                </div>
                <div class="form-group col-sm-4">
                    <select class="select_select0 col-sm-4" name="country" id="country">
                        <option value="">==请选择==</option>
                    @foreach(config('ebaysite.ebay_country')as $key=>$value)
                            <option value="{{$key}}"  {{ Tool::isSelected('country', $key,$model) }} >{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">邮编：</label>
                </div>
                <div class="form-group col-sm-2">
                    <input type="text" class="form-control" name="postal_code" id="postal_code" value="{{$model->postal_code}}">
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
            ];

            $dispatch_time_max =[0,1,2,3,4,5,6,7,8,9];

            $shipping_details = json_decode($model->shipping_details,true);
            ?>

            <div class="row">
                <div class="form-group col-sm-1">
                    <label for="subject" class="right">处理天数：</label>
                </div>
                <div class="form-group col-sm-1">
                    <select class="form-control select_select0 col-sm-1" name="dispatch_time_max" id="dispatch_time_max">
                        @foreach($dispatch_time_max as $v)
                            <option value="{{$v}}"    {{ Tool::isSelected('dispatch_time_max', $v,$model) }} >{{$v}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
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
                            <select class="form-control select_select0 col-sm-1 shipping"
                                    name="shipping[{{$i}}][ShippingService]">
                                <option value=""></option>
                                @foreach($shipping as $ship)
                                    @if(($ship->valid_for_selling_flow==1)&&($ship->international_service==2))
                                        <option value="{{$ship->shipping_service}}"
                                        @if(isset($shipping_details['Shipping'][$i]['ShippingService'])&&($shipping_details['Shipping'][$i]['ShippingService']==$ship->shipping_service))
                                                selected="selected"
                                                @endif
                                                >{{$ship->description}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运费：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control shipping_cost" type="text" name="shipping[{{$i}}][ShippingServiceCost]"
                            @if(isset($shipping_details['Shipping'][$i]['ShippingServiceCost']))
                                value="{{$shipping_details['Shipping'][$i]['ShippingServiceCost']}}"
                            @endif
                                    >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">额外每件加收：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control shipping_addcost" type="text"
                                   name="shipping[{{$i}}][ShippingServiceAdditionalCost]"
                            @if(isset($shipping_details['Shipping'][$i]['ShippingServiceAdditionalCost']))
                                   value="{{$shipping_details['Shipping'][$i]['ShippingServiceAdditionalCost']}}"
                            @endif >
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
                            <select class="form-control select_select0 col-sm-1 international"
                                    name="InternationalShipping[{{$i}}][ShippingService]">

                                <option value=""></option>
                                @foreach($shipping as $ship)
                                    @if(($ship->valid_for_selling_flow==1)&&($ship->international_service==1))
                                        <option value="{{$ship->shipping_service}}"
                                            @if(isset($shipping_details['InternationalShipping'][$i]['ShippingService'])&&($shipping_details['InternationalShipping'][$i]['ShippingService']==$ship->shipping_service))
                                                     selected="selected"
                                                    @endif
                                                >
                                            {{$ship->description}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运费：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control international_cost" type="text"
                                   name="InternationalShipping[{{$i}}][ShippingServiceCost]" @if(isset($shipping_details['InternationalShipping'][$i]['ShippingServiceCost'])) value="{{$shipping_details['InternationalShipping'][$i]['ShippingServiceCost']}}" @endif >

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">额外每件加收：</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input class="form-control international_addcost" type="text"
                                   name="InternationalShipping[{{$i}}][ShippingServiceAdditionalCost]"   @if(isset($shipping_details['InternationalShipping'][$i]['ShippingServiceAdditionalCost'])) value="{{$shipping_details['InternationalShipping'][$i]['ShippingServiceAdditionalCost']}}"  @endif>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="subject" class="right">运输国家：</label>
                        </div>
                        <div class="form-group col-sm-4">
                            <select class="form-control select_select0 col-sm-1 international_ship"
                                    name="InternationalShipping[{{$i}}][ShipToLocation][]" multiple>
                                @foreach(config('ebaysite.ebay_country') as $key=> $v)
                                    <option value="{{$key}}"
                                    @if(isset($shipping_details['InternationalShipping'][$i]['ShipToLocation']))
                                        @if(in_array($v,$shipping_details['InternationalShipping'][$i]['ShipToLocation'])){{'selected="selected"'}}  @endif
                                            @endif
                                            >{{$v}}</option>
                                @endforeach
                            </select>
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
                    <select class="form-control select_select0 col-sm-1"
                            name="un_ship[]" id="un_ship" multiple>
                        @foreach(config('ebaysite.ebay_country') as $key=> $v)
                            <option value="{{$key}}" @if(in_array($key,$shipping_details['ExcludeShipToLocation'])){{'selected="selected"'}}  @endif  >{{$v}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
    <input type="hidden" name="action" id="action" value=""/>
    <input type="hidden" name="id" value="{{$model->id}}" id="id"/>

@stop


@section('formButton')
    <div class="text-center">
        <button type="submit" name="save" class="btn btn-success submit_btn ">保存为草稿</button>
        <button type="submit" name="verify" class="btn btn-warning submit_btn ">检测刊登费用</button>
        <button type="submit" name="prePost" class="btn btn-info  submit_btn ">加入预刊登队列</button>
        <button type="submit" name="editAndPost" class="btn btn-danger  submit_btn ">保存并且发布</button>
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
            $(this).parent().remove();

        });
        $(document).on('click', '.mul-account', function () {
            var listing_type = $(' input[name="listing_type"]:checked ').val();
            if(listing_type==2){
                var sku = $("#ebay_sku").val();
                var price = $("#start_price2").val();
                if(price==''||sku==''){
                    alert("先设置初始价格或sku");
                    return false;
                }
            }
            if(listing_type==3){
                var sku = $(this).parent().children().children().eq(0).val();
                var price = $(this).parent().children().eq(1).children().eq(0).val();
                if(price==''||sku==''){
                    alert("先设置初始价格或sku");
                    return false;
                }
            }
            mulAccount(sku);


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
                        '<input class="form-control" type="text" placeholder="标题" name="title[' + value + ']" maxlength =80></div>' +
                        '<div class="form-group col-sm-1">' +
                        '<button type="button" class="btn btn-success show-sub"  title="填写副标题"><i class="glyphicon glyphicon-plus"></i></button></div></div>' +
                        '<div class="row hidden">' +
                        '<div class="form-group col-sm-1">' +
                        '<label for="subject" class="right"></label>' +
                        '</div>' +
                        '<div class="form-group col-sm-6" name="sub_title[' + value + ']">' +
                        '<input class="form-control" type="text" placeholder="副标题" maxlength=80>' +
                        '</div></div></div>';
                $('#account_tittle').append(add_tittle);
                mulAccountType3();
            } else { //取消
                $("#account_tittle_" + value).remove();
                mulAccountType4();

            }
        });

        function suggestCategory(){
            var key_word = $("#key_word").val();
            var site = $("#site").val();
            $.ajax({
                url: "{{ route('ebayPublish.ajaxSuggestCategory') }}",
                data: {
                    site: site,
                    key_word: key_word
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if(result){
                        var html = '<div class="form-group text-left col-lg-10"><select  size="12" class="form-control category_list" multiple> ';
                        $.each(result, function (index, el) {
                            html += '<option value="' + el.category_id + '" lang="true" data-content="1">' + el.category_full_name + '('+el.percent+'%)</option>';
                        });
                        html = html + ' </select></div>';
                        $('#category-set').empty().append(html);
                    }else{
                        alert("未找到对应分类");
                    }

                }
            });

        }

        function addItem(sku) {
            var html = '<div class="row">' +
                    '<div class="form-group col-sm-1 ">' +
                    '<input type="text" value="' + sku + '"  name="sku[]"  class="form-control text-center  sku-sku" >' +
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
                    '<button type="button" class="btn btn-danger bt-right" title="删除该SKU"><i class="glyphicon glyphicon-trash"></i></button>' +
                    '<button type="button" class="btn btn-info mul-account " title="设置不同账号价格"><i class="glyphicon glyphicon-cog"></i></button></div>';
            $("#variation_sku").append(html);
            if(sku!=''){
                mulAccountType1(sku);            }
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
                        }).empty().append(result.ship_text).val('').trigger("change");
                        $('.international').select2({
                            placeholder: "Select a international shipping",
                            allowClear: true
                        }).empty().append(result.international_text).val('').trigger("change");

                        $("#returns_with_in").empty().append(result.returns_with_in).val('').trigger("change");
                        $("#shipping_costpaid_by").empty().append(result.shipping_costpaid_by).val('').trigger("change");
                        $("#refund").empty().append(result.refund).val('').trigger("change");
                        $("[name = refund_description ]").val('');
                        $("[name = extended_holiday]:checkbox").attr("checked", false);

                        $(".shipping_cost ").each(function(){
                            $(this).val(0.00);
                        });
                        $(".shipping_addcost ").each(function(){
                            $(this).val(0.00);
                        });
                        $(".international_cost ").each(function(){
                            $(this).val(0.00);
                        });
                        $(".international_addcost ").each(function(){
                            $(this).val(0.00);
                        });
                        $(".international_ship ").each(function(){
                            $(this).val('').trigger("change");
                        });
                        $("#un_ship").val('').trigger("change");


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
                        addCondition();
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

                        if(result.is_upc=='Required'){
                            addUserSpecifics(2,'UPC');
                        }
                        if(result.is_ean=='Required'){
                            addUserSpecifics(2,'EAN');
                        }
                        if(result.is_isbn=='Required'){
                            addUserSpecifics(2,'ISNB');
                        }

                    }
                }
            });
        }
        function addUserSpecifics(type,value) {
            if(type==1){
                var str = prompt("新增属性名称");
            }else{
                var str = value;
            }
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
            var sku =  $(e).parent().parent().children().children().eq(0).val();
            mulAccountType2(sku,value,1);
            $(e).css("background-color", '#25fa69');
            var i =0;
            $(".sku-price").each(function () {
                if(i==0){
                    setData(3);
                }
                if ($(this).css("background-color") == 'rgb(250, 54, 88)') {
                    $(this).val(value);
                    var sku =  $(this).parent().parent().children().children().eq(0).val();
                    mulAccountType2(sku,value,1);
                }
                i++;
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
                                '<input type="text" class="hidden" name="variation_picture[' + value + ']" value="">' +
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
        function setData(type){
            var site = $("#site").val();
            var warehouse = $("#warehouse").val();
            var ebay_sku = $('#ebay_sku').val();
            var price = '';
            if(type==1){
                price=$("#start_price1").val();
            }
            if(type==2){
                price=$("#start_price2").val();
                mulAccountType2(ebay_sku,price,1)
            }
            if(type==3){
                var i=0;
                $(".sku-price").each(function () {
                    if(i==0){
                        price = $(this).val();
                    }
                    i++;
                });
            }

            $.ajax({
                url: "{{ route('ebayPublish.ajaxSetDataTemplate') }}",
                data: {
                    site: site,
                    warehouse: warehouse,
                    ebay_sku:ebay_sku,
                    price:price
                },
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    if (result.status) {
                        $("#location").val(result.data.location);
                        $("#country").val(result.data.country).trigger("change");
                        $("#postal_code").val(result.data.postal_code);
                        $("#dispatch_time_max").val(result.data.dispatch_time_max).trigger("change");
                        if(result.data.buyer_requirement.LinkedPayPalAccount){
                            $("[name = no_paypal]:checkbox").attr("checked", !$(this).attr("checked"));
                        }
                        if(result.data.buyer_requirement.ShipToRegistrationCountry){
                            $("[name = no_ship]:checkbox").attr("checked", !$(this).attr("checked"));
                        }
                        if(result.data.buyer_requirement.unpaid_on){
                            $("[name = unpaid_on]:checkbox").attr("checked", !$(this).attr("checked"));
                            $("[name = unpaid]").val(result.data.buyer_requirement.MaximumUnpaidItemStrikesInfo.Count).trigger("change");
                            $("[name = unpaid_day]").val(result.data.buyer_requirement.MaximumUnpaidItemStrikesInfo.Period).trigger("change");
                        }
                        if(result.data.buyer_requirement.policy_on){
                            $("[name = policy_on]:checkbox").attr("checked", !$(this).attr("checked"));
                            $("[name = policy]").val(result.data.buyer_requirement.MaximumBuyerPolicyViolations.Count).trigger("change");
                            $("[name = policy_day]").val(result.data.buyer_requirement.MaximumBuyerPolicyViolations.Period).trigger("change");
                        }
                        if(result.data.buyer_requirement.feedback_on){
                            $("[name = feedback_on]:checkbox").attr("checked", !$(this).attr("checked"));
                            $("[name = feedback]").val(result.data.buyer_requirement.MinimumFeedbackScore).trigger("change");
                        }
                        if(result.data.buyer_requirement.item_count_on){
                            $("[name = item_count_on]:checkbox").attr("checked", !$(this).attr("checked"));

                            $("[name = item_count]").val(result.data.buyer_requirement.MaximumItemRequirements.MaximumItemCount).trigger("change");
                            $("[name = item_count_feedback]").val(result.data.buyer_requirement.MaximumItemRequirements.MinimumFeedbackScore).trigger("change");
                        }
                        var i=0;
                        $(".shipping").each(function(){
                            i++;
                            if (typeof(result.data.shipping_details.Shipping[i]['ShippingService']) != "undefined"){
                                $(this).val(result.data.shipping_details.Shipping[i]['ShippingService']).trigger("change")
                            }

                        });
                        var i=0;
                        $(".international ").each(function(){
                            i++;
                            if (typeof(result.data.shipping_details.InternationalShipping[i]['ShippingService']) != "undefined"){
                                $(this).val(result.data.shipping_details.InternationalShipping[i]['ShippingService']).trigger("change")
                            }
                        });
                        var i=0;
                        $(".shipping_cost ").each(function(){
                            i++;
                            if (typeof(result.data.shipping_details.Shipping[i]['ShippingServiceCost']) != "undefined"){
                                $(this).val(result.data.shipping_details.Shipping[i]['ShippingServiceCost']);
                            }
                        });
                        var i=0;
                        $(".shipping_addcost ").each(function(){
                            i++;
                            if (typeof(result.data.shipping_details.Shipping[i]['ShippingServiceAdditionalCost']) != "undefined"){
                                $(this).val(result.data.shipping_details.Shipping[i]['ShippingServiceAdditionalCost']);
                            }
                        });
                        var i=0;
                        $(".international_cost ").each(function(){
                            i++;
                            if (typeof(result.data.shipping_details.InternationalShipping[i]['ShippingServiceCost']) != "undefined"){
                                $(this).val(result.data.shipping_details.InternationalShipping[i]['ShippingServiceCost']);
                            }
                        });
                        var i=0;
                        $(".international_addcost ").each(function(){
                            i++;
                            if (typeof(result.data.shipping_details.InternationalShipping[i]['ShippingServiceAdditionalCost']) != "undefined"){
                                $(this).val(result.data.shipping_details.InternationalShipping[i]['ShippingServiceAdditionalCost']);
                            }
                        });

                        var i=0;
                        $(".international_ship ").each(function(){
                            i++;
                            if (typeof(result.data.shipping_details.InternationalShipping[i]['ShipToLocation']) != "undefined"){
                                $(this).val(result.data.shipping_details.InternationalShipping[i]['ShipToLocation']).trigger("change");
                            }
                        });

                        $("#un_ship").val(result.data.shipping_details.ExcludeShipToLocation).trigger("change");



                        // 退货政策
                        $("#returns_with_in").val(result.data.return_policy.ReturnsWithinOption).trigger("change");
                        $("#refund").val(result.data.return_policy.RefundOption).trigger("change");
                        $("#shipping_costpaid_by").val(result.data.return_policy.ShippingCostPaidByOption).trigger("change");
                        $("[name = refund_description ]").val(result.data.return_policy.Description);
                        if(result.data.return_policy.ExtendedHolidayReturns){
                            $("[name = extended_holiday]:checkbox").attr("checked", !$(this).attr("checked"));
                        }
                        //  $(".select_select0").trigger("change")
                    }else{
                        alert(result.info)
                    }
                }
            });
        }
        function getSkuPicture() {

        }

        function previewDescription(){
            content.execCommand( 'preview',12312323156 );
        }

        /**
         * type = 1;加sku
         * type = 2 设置价格
         * type  = 3 加账号
         * type  = 4 减账号
         * type = 5 查看
         * random = 1 不变
         * random = 2 随机值
         */
        function mulAccount(sku){

            $(".mul-account-set").each(function(){
                if($(this).attr('data-target')==sku){
                    if($(this).hasClass("hidden")){
                        $(this).removeClass("hidden");
                    }
                }else{
                    if(!$(this).hasClass("hidden")){
                        $(this).addClass("hidden");
                    }
                }
            });
            $('#mulAccount').modal('show');
        }
        function mulAccountType1(sku){
            var is_not_exist = true;
            $(".mul-account-set").each(function(){
                if($(this).attr('data-target')==sku){
                    is_not_exist = false;
                }
            });
            if(is_not_exist){
                var last_html = '<div class="row mul-account-set"  data-target="'+sku+'"></div>';
                $("#mulAccountName").text(sku);
                $('#mulAccountSet').append(last_html)
            }

        }
        function mulAccountType2(sku,price,random){

            var is_not_exist = true;
            $(".mul-account-set").each(function(){
                if($(this).attr('data-target')==sku){
                    is_not_exist = false;
                }
            });
            if(is_not_exist){
                mulAccountType1(sku)
            }
            var priceArr = new Array();
            $(".mul-account-set").each(function(){
                var i = 0;
                var html = '';
                if($(this).attr('data-target')==sku){
                    var mark  =  $(this);
                    $(".choose_account").each(function(){
                        var new_price = price;
                        if($(this).is(':checked')) {// 选中
                            var text_name = $(this).parent().text();
                            var account_id = $(this).val();
                            sku = Trim(sku);
                            var new_target = sku+'_'+account_id; //undefined
                            if(typeof(priceArr[i]) == "undefined"){ //
                                priceArr = getRandArray(price,priceArr);
                            }

                            new_price = priceArr[i];
                            mark.children().each(function(){
                                if(new_target==$(this).attr("data-target")){
                                    if(random==2){ //原来的值
                                        new_price = $(this).children().eq(1).val();
                                    }
                                }
                            });
                            html = html+'<div class="form-group col-sm-3" data-target="'+new_target+'" >' +
                            '<label class="right">'+text_name+'：</label>' +
                            '<input type="text"  class="form-control " placeholder="价格" value="'+new_price+'" name=mulAccount['+sku+']['+account_id+']>' +
                            '</div>';
                            i++;
                        }
                    });
                    mark.empty().append(html);
                    $("#mulAccountName").text(sku);
                }
            });
        }
        function mulAccountType3(){

            var type  = $('input[name="listing_type"]:checked ').val();
            if(type==3){
                $(".sku-sku").each(function(){
                    var sku = $(this).val();
                    var price = $(this).parent().next().children().eq(0).val();
                    mulAccountType2(sku,price,1);
                });
            }
            if(type==2){
                var sku = $("#ebay_sku").val();
                var price = $("#start_price2").val();
                mulAccountType2(sku,price,1);

            }

        }
        function mulAccountType4() {
            $(".choose_account").each(function () {
                if (!$(this).is(':checked')) {// 选中
                    var account_id = $(this).val();
                    $(".mul-account-set").each(function () {
                        var sku = $(this).attr('data-target');
                        $(this).children().each(function () {
                            var new_target = sku + '_' + account_id;
                            if ($(this).attr('data-target') == new_target) {
                                $(this).remove();
                            }
                        })
                    });
                }
            });
        }
        function getRandArray(price,priceArr){
            if(typeof(priceArr[0]) == "undefined"){ //
                var price_new = parseFloat(price)+0.01;
                priceArr[0] = price_new.toFixed(2);
                return priceArr;
            }else{

                var price_new = priceArr[priceArr.length-1];
                // price_new = parseFloat(price_new)+0.01;
                price_new = getNewPirce(priceArr);
                priceArr[priceArr.length] = price_new;
                return priceArr;
            }
        }

        function getNewPirce(priceArr){
            var price = priceArr[priceArr.length-1];

            for(var k=0;k<100;k++){
                var rand = Math.random();
                if(rand>0.75){
                    var type  = 1;
                }
                if(type==1){
                    price =  parseFloat(price)+0.01;
                }else{
                    price =  parseFloat(price)-0.01;
                }
                price =  price.toFixed(2);
                if((price>0.99)&&(priceArr.indexOf(price)==-1)){
                    break;
                }
            }
            return price;
        }
        function Trim(str)
        {
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

    </script>
@stop
@section('childJs')@show