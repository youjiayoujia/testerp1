<?php

namespace App\Models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class smtProductSku extends Model
{
    //
    protected  $table = "smt_product_skus";
    protected  $fillable = [
                'skuMark',
                'skuCode',
                'smtSkuCode',
                'productId',
                'sku_active_id',
                'skuPrice',
                'skuStock',
                'propertyValueId',
                'skuPropertyId',
                'propertyValueDefinitionName',
                'synchronizationTime',
                'profitRate',
                'isRemove',
                'is_new',
                'is_erp',
                'ipmSkuStock',
                'aeopSKUProperty',
                'overSeaValId',
                'updated',
                'lowerPrice',
                'discountRate'
                ];
    
   
}
