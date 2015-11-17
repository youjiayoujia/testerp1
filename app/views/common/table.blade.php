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
            <tbody>
            @section('tableBody')
            @show{{-- 表格数据 --}}
            </tbody>
            <tfoot>
            {!! $data->render() !!}
            </tfoot>
        </table>
    </div>
@stop