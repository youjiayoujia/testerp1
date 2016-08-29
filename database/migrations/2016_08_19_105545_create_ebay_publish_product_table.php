<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayPublishProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_publish_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->comment('账号id');
            $table->string('item_id')->comment('ebay产品id');
            $table->string('primary_category')->comment('第一分类');
            $table->string('secondary_category')->comment('第二分类');
            $table->string('title')->comment('标题');
            $table->string('sub_title')->comment('子标题');
            $table->string('sku')->comment('ebay_sku');
            $table->string('site_name')->comment('站点名称');
            $table->string('site')->comment('站点id');
            $table->float('start_price')->comment('售价');
            $table->integer('quantity')->comment('数量');
            $table->float('reserve_price')->comment('');
            $table->float('buy_it_now_price')->comment('');
            $table->string('listing_type')->comment('ebay产品上架类型');
            $table->string('view_item_url')->comment('ebay产品链接');
            $table->string('listing_duration')->comment('ebay产品上架天数');
            $table->string('dispatch_time_max')->comment('处理天数');
            $table->string('private_listing')->comment('是否为私人拍卖');
            $table->string('payment_methods')->comment('付款方式');
            $table->string('paypal_email_address')->comment('收款paypal');
            $table->string('currency')->comment('币种');
            $table->string('location')->comment('ebay产品所在地');
            $table->string('postal_code')->comment('ebay产品所在地邮编');
            $table->integer('quantity_sold')->comment('广告售出量');
            $table->string('store_category_id')->comment('店铺分类id');
            $table->string('condition_id')->comment('物品状况');
            $table->string('condition_description')->comment('物品状况描述');
            $table->text('picture_details')->comment('橱窗图片');
            $table->text('item_specifics')->comment('物品属性');
            $table->text('variation_picture')->comment('多属性图片');
            $table->text('variation_specifics')->comment('多属性属性');
            $table->text('return_policy')->comment('退货政策');
            $table->text('shipping_details')->comment('物流政策');
            $table->enum('status', ['0', '1','2','3'])->comment('0草稿 1待发布 2在线 3已下架')->default('0');
            $table->enum('is_out_control', ['0', '1'])->comment('0否 1是')->default('0');
            $table->enum('multi_attribute', ['0', '1'])->comment('0否 1是')->default('0');
            $table->string('seller_id')->comment('所属销售');
            $table->text('description')->comment('描述');
            $table->dateTime('start_time')->comment('上架时间');
            $table->dateTime('update_time')->comment('最后同步时间');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ebay_publish_product');
    }
}
