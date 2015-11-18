@extends('layouts.default')
@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column['label'] }}</th>
                @endforeach
            </tr>
            </thead>
            <tfoot>
            {!! $data->render() !!}
            </tfoot>
            <tbody>
            @section('tableBody')
            @show{{-- 表格数据 --}}
            </tbody>
        </table>
    </div>
@stop