@extends('common.detail')
@section('detailBody')
    <div class="panel panel-default">
        <div class="panel-heading">三宝产品操作 :</div>
        <div class="row">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="col-lg-3">
                <strong>批量上传三宝产品</strong>:  
            </div>
            <div class="col-lg-3">
               <input type="file" class="file" name="products" value=""/> 
            </div>
            <div class="col-lg-3">
            <input type="submit" class="submit" >
            </form>
                <a href="">上传格式下载</a>( CSV字段名称: sku,hs_code,unit,f_model )  
            </div>
        </div>
         <div class="row">
            <div class="col-lg-3">
                <strong>ID</strong>:  
            </div>
            <div class="col-lg-3">
                <strong>spu</strong>:  
            </div>
            <div class="col-lg-3">
                <strong>model</strong>:  
            </div>
        </div>
         <div class="row">
            <div class="col-lg-3">
                <strong>ID</strong>:  
            </div>
            <div class="col-lg-3">
                <strong>spu</strong>:  
            </div>
            <div class="col-lg-3">
                <strong>model</strong>:  
            </div>
        </div>
         <div class="row">
            <div class="col-lg-3">
                <strong>ID</strong>:  
            </div>
            <div class="col-lg-3">
                <strong>spu</strong>:  
            </div>
            <div class="col-lg-3">
                <strong>model</strong>:  
            </div>
        </div>      
    </div>

     
@stop