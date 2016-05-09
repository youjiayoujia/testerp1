@extends('common.detail')
@section('detailBody')
    <div class="col-lg-12">
        <table class="table table-bordered table-striped table-hover sortable">
            <thead>
            <tr>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
            </tr>
            </thead>
            <tbody>
            @section('tableBody')@show{{-- 列表数据 --}}
            </tbody>
        </table>
    </div>
@stop