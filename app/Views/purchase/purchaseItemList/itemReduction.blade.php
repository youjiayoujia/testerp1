@extends('common.form')
@section('formAction') /purchaseItemList/reductionUpdate  @stop
@section('formAttributes') name='creator' enctype="multipart/form-data" @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="row">
      <div class="form-group col-lg-3">
        <label for="type">还原采购条目的ID：</label>
         <textarea name="purchaseItemIds" rows="4" cols="50" autofocus>
         </textarea>
    </div>
    </div>
@stop
 
 
 
 