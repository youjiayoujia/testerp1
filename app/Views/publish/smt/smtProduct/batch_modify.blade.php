<?php
/**
 * 批量修改在线产品模板
 */
?>
<style>
    .dia input {
        height: 25px;
        width: 257px;
        margin: 1px;
    }
    input.text-90 {
        width: 90px;
    }
    input.text-60 {
        width: 60px;
    }
    .dia .rad {
        margin: 0px;
        width: 15px;
    }
    .dia select.sel-qu {
        margin-left: 0;
        width: 70px;
    }
    .dia td.td-left{text-align:right;padding-right:5px}
    .dia td.td-right {
        text-align: left;
        padding-left: 30px;
    }
    input,label { vertical-align:middle;}
    .layer_pageContent{padding-top: 10px;padding-left: 10px;}
    .proCon, #msgList {
        width: 690px;
        height: 320px;
        overflow-y: auto;
    }
    .proCon .proList{
        width: 100%;
    }
    .proList thead td, .proList tbody td {
        background-color: #EBF3FF;
        border-top: 1px solid #DBE9FF;
        color: #677DA1;
        font-weight: 100;
        height: 30px;
        padding: 10px 10px;
    }
    .proList .td-right {
        text-align: right;
    }
    .pagination {
        margin-top: 10px;
        padding: 0px 18px;
        height: 21px;
        border-bottom: 1px solid #CCC;
        text-align: center;
        font: 400 11px tahoma;
        background: #F0F0F4;
        width: 690px;
    }
    .pagination .page-number {
        font-weight: 700;
        line-height: 22px;
        float: left;
    }
    .pagination .page-skip {
        float: right;
        line-height: 22px;
    }
    .pagination .page-skip .page-skip-text{
        font-size: 11px;
    }
    .pagination .page-skip-button{
        line-height: 14px;
        font-size: 12px;
    }
    .pagination .page-links {
        display: inline-block;
        zoom: 1;
    }
    .page-links {
        overflow: hidden;
    }
    .pagination .page-skip-text {
        padding: 2px 0;
        height: 14px;
        line-height: 14px;
        width: 35px;
    }
    .pagination .page-prev, .pagination .page-next {
        height: 14px;
        border: 1px solid;
        line-height: 22px;
    }
    .pagination .page-prev a, .pagination .page-next a{
        text-underline: none;
    }
</style>
@extends('layouts.default')
<div class="container-fluid">

    <div class="row" style="padding: 15px 30px 0px;">
        <!--添加产品按钮下边一个是一样的-->
        <div class="form-group">
            <div class="col-sm-2">
                <a class="btn btn-primary btn-sm addProduct">添加更多产品</a>
            </div>

            <div class="col-sm-offset-5">
                <a class="btn btn-primary btn-sm disabled submitModify" href="#">提交</a>
            </div>

        </div>

        <div class="alert-warning" style="margin:10px auto;">
            发布涉嫌侵犯知识产权产品将会受到处罚，请在发布前仔细阅读<a href="http://seller.aliexpress.com/rule/rulecate/intellectual01.html" target="_blank">知识产权规则</a>。品牌点击查看
            <a href="http://seller.aliexpress.com/education/rule/product/brand.html" target="_blank">品牌列表参考。</a>
            <?php
            if ($from != 'draft'){
                echo '<p class="red">修改在线产品时，点击提交后将会实时变更线上产品数据</p>';
            }else {
                echo '<p class="red">修改草稿和待发布数据，提交后只会变更本地数据</p>';
            }
            ?>
        </div>

        <!--产品列表-->
        <table class="table table-bordered table-condensed" id="up-proList">
            <colgroup>
                <col width="6%"/>
                <col/>
                <col width="8%"/>
                <col width="11%"/>
                <col width="8%"/>
                <col width="10%"/>
                <col width="9%"/>
                <col width="8%"/>
                <col width="8%"/>
                <col width="10%"/>
                <col width="3%"/>
            </colgroup>
            <thead>
            <tr>
                <td>&nbsp;</td>
                <td>
                    产品标题<a class="p-info" href="#">修改</a>
                </td>
                <td>
                    关键词<a class="p-keyword" href="#">修改</a>
                </td>
                <td>
                    销售单位/方式<a class="p-sell" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="销售的单位和方式，可选择单价零售或打包销售"></a>
                </td>
                <td>
                    包装重量<a class="p-quality" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="包装重量=产品净重+外包装重量"></a>
                </td>
                <td>
                    包装尺寸<a class="p-size" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="产品包装以后的长、宽、高"></a>
                </td>
                <td>
                    产品信息模块<a class="p-detail" href="#">修改</a>
                </td>
                <td>
                    服务模板<a class="p-serve" href="#">修改</a>
                </td>
                <td>
                    运费模板<a class="p-module" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="国际物流的运费设置，新手推荐“新手运费模板”"></a>
                </td>
                <td>
                    零售价(US $)<a class="p-price" href="#">修改</a>
                    <a class="icon icon-info-sign" href="javascript:;" title="“零售价”为淘宝原价折算成美金价格。需要设置利润后方可销售"></a>
                </td>
                <td></td>
            </tr>
            </thead>

            <!--先输出错误信息-->
            <?php if (!empty($error)):?>
            <tbody>
            <tr>
                <td colspan="11">
                    <div class="alert alert-danger"><?php echo $error;?></div>
                </td>
            </tr>
            </tbody>

            <?php else:?>

            <!--产品列表，一个产品一个tbody-->
            <?php
            if (!empty($productList)):
                $unitId = '100000015';
                //$token_id = 0;
                foreach ($productList as $key => $product):
                    $detail = $product->details;
                    $unitId = $detail->productUnit;
                    //$token_id = $token_id ? $token_id : $product['token_id'];
            ?>
            <tbody>
            <tr>
                <td>
                    <?php
                    //产品图片
                    $imageUrl = '';
                    if (!empty($detail['imageURLs'])){
                        $imageUrls = explode(';', $detail['imageURLs']);
                        $imageUrl = array_shift($imageUrls);
                    }
                    ?>
                    <img src="<?php echo $imageUrl;?>" alt="产品图片" width="80" height="80"/>
                </td>
                <td>
                    <span class="s-ti"><?php echo $product['subject'];?></span>
                    <input type="hidden" name="subject" value="<?php echo $product['subject'];?>">
                </td>
                <td>
                    <span class="s-k1"><?php echo $detail['keyword'];?></span>
                    <input type="hidden" name="keywords" value="<?php echo $detail['keyword'];?>"/>
                    <br>
                    <span class="s-k2"><?php echo $detail['productMoreKeywords1']?></span>
                    <input type="hidden" name="productMoreKeywords1" value="<?php echo $detail['productMoreKeywords1']?>"/>
                    <br>
                    <span class="s-k3"><?php echo $detail['productMoreKeywords2']?></span>
                    <input type="hidden" name="productMoreKeywords2" value="<?php echo $detail['productMoreKeywords2']?>"/>
                </td>
                <td>
                    <span class="s-se">按<?php echo $unitList[$detail['productUnit']]['name'].' ('.$unitList[$detail['productUnit']]['name_en'].')';?>出售</span>
                    <input type="hidden" name="packageWay" value="<?php echo ($detail['packageType'] == 1 ? 'true' : 'false').'-'.$detail['productUnit'].'-'.$detail['lotNum'];?>">
                </td>
                <td>
                    <div>
                        <span class="s-qu"><?php echo $product['grossWeight'];?></span>公斤
                        <input type="hidden" name="grossWeight" value="<?php echo $product['grossWeight'];?>">
                    </div>
                </td>
                <td>
                    <div class="td-size">
                        <div>
                            <span class="s1">长：</span>
                            <span class="s-l"><?php echo (int)$product['packageLength'];?></span>
                            <input type="hidden" name="packageLength" value="<?php echo (int)$product['packageLength'];?>">
                            厘米
                        </div>
                        <div>
                            <span class="s1">宽：</span>
                            <span class="s-w"><?php echo (int)$product['packageWidth'];?></span>
                            <input type="hidden" name="packageWidth" value="<?php echo (int)$product['packageWidth'];?>">
                            厘米
                        </div>
                        <div>
                            <span class="s1">高：</span>
                            <span class="s-h"><?php echo (int)$product['packageHeight'];?></span>
                            <input type="hidden" name="packageHeight" value="<?php echo (int)$product['packageHeight'];?>">
                            厘米
                        </div>
                    </div>
                </td>
                <td>
                    <div class="td-size">
                        <div>
                            <span class="sl-t"></span>
                            <input type="hidden" name="tModuleId">
                            <input type="hidden" name="tModuleName">
                            <input type="hidden" name="tModuleType">
                        </div>
                        <div>
                            <span class="sl-b"></span>
                            <input type="hidden" name="bModuleId">
                            <input type="hidden" name="bModuleName">
                            <input type="hidden" name="bModuleType">
                        </div>
                    </div>
                </td>
          
                <td>
                    <span class="sku-price" style="background: lightskyblue; color: white;">零</span>
                    <span class="s-pr"><?php echo ($product['productMinPrice'] == $product['productMaxPrice'] ? $product['productMinPrice'] : $product['productMinPrice'].' - '.$product['productMaxPrice']);?></span>
                    <input type="hidden" name="priceCreaseNum" value="">
                    <input type="hidden" name="priceCreaseType" value="">
                    <input type="hidden" name="isSKU" value="true">
                </td>
                <td>
                    <a class="icon-trash red bigger-130 pro-remove" href="#"></a>
                    <input type="hidden" name="productId" value="<?php echo $product['productId'];?>"/>
                    <input type="hidden" name="categoryId" value="<?php echo $product['categoryId'];?>"/>
                    <input type="hidden" name="changed" value="false"/>
                </td>
            </tr>
            </tbody>
            <?php
                endforeach;
            endif;
            endif;
            ?>
        </table>

        <!--添加产品按钮最上边一个是一样的-->
        <div class="form-group">
            <div class="col-sm-2">
                <a class="btn btn-primary btn-sm addProduct">添加更多产品</a>
            </div>

            <div class="col-sm-offset-5">
                <a class="btn btn-primary btn-sm disabled submitModify" href="#">提交</a>
            </div>
        </div>
    </div>

    <!--标题-->
    <div id="dia-info" class="hide">
        <table class="dia dia-info">
            <tbody>
            <tr>
                <td width="105" class="td-left">标题开头添加</td>
                <td width="270"><input type="text" name="startTitle" maxlength="128"></td>
            </tr>
            <tr>
                <td width="105" class="td-left">标题结尾添加</td>
                <td width="270"><input type="text" name="endTitle" maxlength="128"></td>
            </tr>
            <tr>
                <td width="105" class="td-left">标题中的</td>
                <td width="270">
                    <input type="text" class="text-90" name="oldTitle" maxlength="128">
                    替换为
                    <input type="text" value="" class="text-90" name="newTitle" maxlength="128">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:35px;"><span class="dia-tip">小提示：对标题进行修改将导致产品重新审核</span></td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--关键词-->
    <div id="dia-keyword" class="hide">
        <table class="dia dia-keyword">
            <tbody>
            <tr>
                <td width="130" class="td-left">替换产品关键词</td>
                <td width="245"><input type="text" value="" name="key1" maxlength="128"></td>
            </tr>
            <tr>
                <td width="130" class="td-left">替换更多关键词</td>
                <td width="245"><input type="text" value="" name="key2" maxlength="50"></td>
            </tr>
            <tr>
                <td width="130" class="td-left"></td>
                <td width="245"><input type="text" value="" name="key3" maxlength="50"></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:66px;"><span class="dia-tip">小提示：对关键词进行修改将导致产品重新审核</span></td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--销售方式-->
    <div id="dia-sell" class="hide">
        <table class="dia">
            <tbody>
            <tr>
                <td width="95" class="td-left">最小计量单位</td>
                <td width="280">
                    <select name="unit-sel">
                        <?php
                        $unitId = !empty($unitId) ? $unitId : '100000015';
                        $unit_ch = '';
                        $unit_en = '';
                        foreach ($unitList as $unit){
                            if ($unitId == $unit['id']){
                                $unit_ch = $unit['name'];
                                $unit_en = $unit['name_en'];
                            }
                            echo '<option value="'.$unit['id'].'" '.($unitId == $unit['id'] ? 'selected="selected"' : '').'>'.$unit['name'].'('.$unit['name_en'].')'.'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="80" class="td-left">销售方式</td>
                <td width="290">
                    <input style="width:14px;" type="radio" value="0" name="sell-by" checked="checked"><span><?php echo $unit_ch.'('.$unit_en.')';?></span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input style="width:14px;" type="radio" value="1" name="sell-by">打包出售 每包
                    <input type="text" name="sell-num" disabled="disabled" class="text-60" maxlength="6"><span><?php echo $unit_ch.'('.$unit_en.')';?></span>
              
            <tr>
                <td width="178" class="td-right">
                    <input class="rad" type="radio" name="quality" value="1">
                    <label>按</label>
                    <select class="sel-qu">
                        <option value="0">重量</option>
                        <option value="1">百分比</option>
                    </select>
                    <label>增加</label>
                </td>
                <td width="197">
                    <input type="text" class="text-90" disabled="disabled" name="add-quality" maxlength="10">
                    <span class="s-unit">公斤</span>
                </td>
            </tr>
            <tr>

                <td colspan="2" style="padding-left:71px;"><span class="dia-tip">小提示：如果减少，可以输入负数</span></td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--尺寸-->
    <div id="dia-size" class="hide">
        <table class="dia">
            <tbody>
            <tr>
                <td width="125" class="td-left">长</td>
                <td width="250"><input class="text-90" type="text" name="len" maxlength="3">厘米</td>
            </tr>
            <tr>
                <td width="125" class="td-left">宽</td>
                <td width="250"><input class="text-90" type="text" name="wid" maxlength="3">厘米</td>
            </tr>
            <tr>
                <td width="125" class="td-left">高</td>
                <td width="250"><input class="text-90" type="text" name="hei" maxlength="3">厘米</td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--产品信息模板-->
    <div id="dia-detail" class="hide">
        <table class="dia">
            <tbody>
            <tr height="30">
                <td width="125" class="td-left">详细描述顶部：</td>
                <td width="250"><a href="#" class="yellowish-btn addModule btn btn-sm" extattr="top">选择产品信息模版</a></td>
            </tr>
            <tr height="30">
                <td class="td-left">已选择：</td>
                <td>
                    <span class="sel-t">--</span>
                    <input type="hidden" name="t-temp-id">
                    <input type="hidden" name="t-temp-name">
                    <input type="hidden" name="t-temp-type">
                </td>
            </tr>
            <tr height="30">
                <td class="td-left">详细描述底部：</td>
                <td><a href="#" class="yellowish-btn addModule btn btn-sm" extattr="bottom">选择产品信息模版</a></td>
            </tr>
            <tr height="30">
                <td class="td-left">已选择：</td>
                <td>
                    <span class="sel-b">--</span>
                    <input type="hidden" name="b-temp-id">
                    <input type="hidden" name="b-temp-name">
                    <input type="hidden" name="b-temp-type">
                </td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--服务模板-->
    <div class="hide" id="dia-serve">
        <table class="dia dia-module">
            <tbody>
            <tr>
                <td width="375">
                    <div class="mo-contain">
                        <img alt="loading" src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--运费模板-->
    <div id="dia-module" class="hide">
        <table class="dia dia-module">
            <tbody>
            <tr>
                <td width="375">
                    <div class="mo-contain">
                        <img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--零售价-->
    <div id="dia-price" class="hide">
        <table class="dia">
            <tbody>
            <tr class="dia-price-tdx" style="display:none;">
                <td colspan="2" style="padding-left:56px;color:red;">已售出代销产品平均加价幅度为20%-50%</td>
            </tr>
            <tr>
                <td class="td-left" width="175">
                    按
                    <select class="sel-qu">
                        <option value="0">金额</option>
                        <option value="1">百分比</option>
                    </select>
                    增加
                </td>
                <td width="200">
                    <input type="text" class="text-90" value="" name="price" maxlength="10" autocomplete="false">
                    <span class="span-unit">美元</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:56px;"><span class="dia-tip">小提示：如果减少，可输入负数。</span></td>
            </tr>
            </tbody>
        </table>
        <div class="error-box"></div>
    </div>

    <!--选择更多产品-->
    <div id="addProSup" class="hide">
        <div class="addProList">
            <div class="search-bar">
                <input type="text" name="subject" class="txt-tip" maxlength="128">
                <select name="productGroup">
                    <option value="0" selected="selected">产品分组</option>
                    <option value="-2">All</option>
                    <?php
                    if (!empty($groupList)):
                        foreach ($groupList as $group){
                            if (array_key_exists('child', $group) && !empty($group['child'])){
                                echo '<optgroup label="'.$group['group_name'].'">';
                                foreach ($group['child'] as $row){
                                    echo '<option value="'.$row['group_id'].'">'.$row['group_name'].'</option>';
                                }
                                echo '</optgroup>';
                            }else {
                                echo '<option value="'.$group['group_id'].'">'.$group['group_name'].'</option>';
                            }
                        }
                    endif;
                    ?>
                </select>
                <!--<select name="memberId">
                    <option value="" selected="selected">产品负责人</option>
                    <option value="All">All</option>
                    <option value="cn1512099214">wei su</option>
                </select>-->
                <?php if ($from != 'draft'):?>
                <select name="offLineTime">
                    <option value="0" selected="selected">到期时间</option>
                    <option value="-1">All</option>
                    <option value="3">剩余3天内</option>
                    <option value="7">剩余7天内</option>
                    <option value="30">剩余30天内</option>
                </select>
                <?php endif;?>
                <input type="hidden" name="from" id="from" value="<?php echo $from;?>" />
                <input type="button" value="搜索" class="btn-submit-m btn btn-sm" name="btn-submit">

                <div style="display: none;" class="product_nil_tip"><span class="me_tip">请输入或选择查询条件</span></div>
            </div>
        </div>
        <div class="proCon">
            <table class="proList">
                <thead>
                <tr>
                    <td width="100">图片</td>
                    <td width="220">产品名称 :</td>
                    <td width="90">负责人</td>
                    <td width="120">售价(US$)</td>
                    <td width="70" class="td-right">
                        全选
                        <input type="checkbox" name="p-selAll">
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="5" style="text-align:center">
                        <img src="http://i02.i.aliimg.com/images/eng/wholesale/icon/loading-middle.gif" alt="loading" style="width:32px; height:32px">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="pagination clearfix hide">
            <div class="page-number">Page <span class="currentpage">1</span> of <span class="totalPage">1</span></div>
            <div class="page-skip">
                Go to Page
                <input type="text" class="page-skip-text" value="">
                <input type="button" class="page-skip-button" value="GO">
            </div>
            <div class="page-links clearfix">
                <a class="page-prev" href="#">Previous</a>
                <a class="page-next" href="#">Next</a>
            </div>
        </div>
    </div>

    <div id="msgList" class="hide">
        <div class="loading center">修改中，请不要操作...</div>
        <div class="center completed hide">操作已完成，如有错误信息，请注意查看</div>
        <div class="alert alert-warning">
        </div>
    </div>

</div>
@section('pageJs')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/layer/layer.min.js') }}"></script>
<script type="text/javascript">
   
</script>
@stop