@extends('common.table')
@section('title') 选款需求列表 @stop
@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="/">主页</a></li>
        <li><a href="{{ route('fashion.index') }}">选款需求</a></li>
        <li class="active">列表</li>
    </ol>
@stop
@section('tableTitle') 选款需求列表 @stop
@section('tableBody')
    @foreach($data as $fashion)
        <tr>
            <td>{{ $fashion->id }}</td>
            <td>@if($fashion->img1)
                    <img src="{{ $fashion->img1 }}" alt='' class='img-rounded' width='170px' height='100px'/>
                @endif
            </td>        
            <td>@if($fashion->img1)
                    <img src="{{ $fashion->img2 }}" alt='' class='img-rounded' width='170px' height='100px'/>
                @endif
            </td>    
            <td>@if($fashion->img1)
                    <img src="{{ $fashion->img3 }}" alt='' class='img-rounded' width='170px' height='100px'/>
                @endif
            </td>    
            <td>@if($fashion->img1)
                    <img src="{{ $fashion->img4 }}" alt='' class='img-rounded' width='170px' height='100px'/>
                @endif
            </td>    
            <td>@if($fashion->img1)
                    <img src="{{ $fashion->img5 }}" alt='' class='img-rounded' width='170px' height='100px'/>
                @endif
            </td>    
            <td>@if($fashion->img1)
                    <img src="{{ $fashion->img6 }}" alt='' class='img-rounded' width='170px' height='100px'/>
                @endif
            </td>    
            <td>{{ $fashion->name }}</td>
            <td>{{ $fashion->address }}</td>
            <td>{{ $fashion->similar_sku }}</td>
            <td>{{ $fashion->competition_url }}</td>
            <td>{{ $fashion->remark }}</td>
            <td>{{ $fashion->expected_date }}</td>
            <td>{{ $fashion->needer_id }}</td>
            <td>{{ $fashion->needer_shopid }}</td>
            <td>{{ $fashion->status }}</td>
            <td>{{ $fashion->user_id }}</td>
            <td>{{ $fashion->handle_time }}</td>
            <td>{{ $fashion->created_at }}</td>
            <td>
                <a href="{{ route('fashion.show', ['id'=>$fashion->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <a href="{{ route('fashion.edit', ['id'=>$fashion->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $fashion->id }}"
                   data-url="{{ route('fashion.destroy', ['id' => $fashion->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop
