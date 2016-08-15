<?php

namespace App\Models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class smtProductDetail extends Model
{
    protected $table = 'smt_product_detail';

    protected $fillable = [
        'productId',
        'aeopAeProductPropertys',
        'imageURLs',
        'detail',
        'keyword',
        'productMoreKeywords1',
        'productMoreKeywords2',
        'productUnit',
        'isImageDynamic',
        'isImageWatermark',
        'lotNum',
        'bulkOrder',
        'packageType',
        'isPackSell',
        'bulkDiscount',
        'promiseTemplateId',
        'freightTemplateId',
        'templateId',
        'shouhouId',
        'detail_title',
        'sizechartId',
        'src',
        'detailPicList',
        'detailLocal',
        'relationProductIds',
        'relationLocation'
    ];
    
    protected $searchFields = [];
    
    protected $rules = [];
}
