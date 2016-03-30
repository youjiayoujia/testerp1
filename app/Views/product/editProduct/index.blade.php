@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-filter"></i> 过滤
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ DataList::filtersEncode(['status','=','0']) }}">未编辑产品</a></li>
                <li><a href="{{ DataList::filtersEncode(['status','=','1']) }}">未编辑图片</a></li>
                <li><a href="{{ DataList::filtersEncode(['status','=','2']) }}">待审核</a></li>
                <li><a href="{{ DataList::filtersEncode(['status','=','3']) }}">已审核</a></li>
            </ul>
    </div>  
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    
    <th>ID</th>
    <th class="sort" data-field="model">MODEL</th>
    <th>分类</th>
    <th>图片</th>
    <th>状态</th>
    <th>选中shop</th>
    <th class="sort" data-field="c_name">中文名称</th>
    <th>材质</th>
    <th>线上供货商</th>
    <th>线上供货商地址</th>
    <th>拿货价</th>
    <th>选款人ID</th>
    <th>状态</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th>操作</th>
@stop
@section('tableBody')
    @foreach($data as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->model }}</td>
            <td>{{ $product->catalog->name }}</td>
            <td>@if($product->default_image>0)<a href="{{ asset($product->image->path) }}/{{$product->image->name}}"><img src="{{ asset($product->image->path) }}/{{$product->image->name}}" width="100px" ></a>@else无图片@endif</td>
            <td><?php if($product->status==0)echo "New";if($product->status==1)echo "Picked";if($product->status==2)echo "Cancel"; ?></td>
            <td><?php if($product->amazonProduct)echo "amazon,";if($product->ebayProduct)echo "ebay,";if($product->aliexpressProduct)echo "aliexpress,";if($product->b2cProduct)echo "B2C,"; ?></td>
            <td>{{ $product->c_name }}</td>
            <td>{{ $product->fabric }}</td>
            <td>{{ $product->supplier->name }}</td>
            <td>{{ $product->supplier_info }}</td>
            <td>{{ $product->purchase_price }}</td>
            <td>{{ $product->upload_user }}</td>
            <?php switch ($product->edit_status) {
                case '0':
                    ?>
                    <td>未编辑资料</td>
                    <?php
                    break;

                case '1':
                    ?>
                    <td>已编辑资料</td>
                    <?php
                    break;

                case '2':
                    ?>
                    <td>已编辑图片</td>
                    <?php
                    break;

                case '3':
                    ?>
                    <td>审核通过</td>
                    <?php
                    break;
            } ?>
            <td>{{ $product->created_at }}</td>
            <td>
                <a href="{{ route('EditProduct.show', ['id'=>$product->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-eye-open"></span> 查看
                </a>
                <?php if($product->edit_status==2){ ?>
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                           data-id="{{ $product->id }}"
                           data-url="{{route('examineAmazonProduct')}}"
                           data-status="3" >
                            <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$product->id}}'>审核</span>
                    </a>
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                           data-id="{{ $product->id }}"
                           data-url="{{route('examineAmazonProduct')}}"
                           data-status="0" >
                            <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$product->id}}'>审核不通过</span>
                    </a>
                <?php }elseif($product->edit_status==3){ ?>
                    <a href="javascript:" class="btn btn-info btn-xs examine_model"
                           data-id="{{ $product->id }}"
                           data-url="{{route('examineAmazonProduct')}}"
                           data-status="0" >
                            <span class="glyphicon glyphicon-check"></span> <span class='examine_{{$product->id}}'>撤销审核</span>
                    </a>
                <?php }elseif($product->edit_status==0){ ?>
                    <a href="{{ route('EditProduct.edit', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑资料
                </a>
                <?php }elseif($product->edit_status==1){ ?>
                    <a href="{{ route('amazonProductEditImage', ['id'=>$product->id]) }}" class="btn btn-warning btn-xs">
                        <span class="glyphicon glyphicon-pencil"></span> 编辑图片
                    </a>  
                <?php } ?>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $product->id }}"
                   data-url="{{ route('EditProduct.destroy', ['id' => $product->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach
@stop

@section('childJs')
    <script type="text/javascript">
       
        </script>
@stop