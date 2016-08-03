@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> 查询当前状态
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach(config('spu.status') as $key=>$value)
                <li><a href="{{ DataList::filtersEncode(['status','=',$key]) }}">{{$value}}（{{$num_arr[$key]}}）</a></li>
            @endforeach
        </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> 全选</th>
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>图片</th>
    <th class="sort" data-field="created_at">创建时间</th>
    <th class="sort" data-field="updated_at">更新时间</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $spu)
        <tr>
            <td><input type="checkbox" name="tribute_id" value="{{$spu->id}}"></td>
            <td>{{ $spu->id }}</td>
            <td>{{ $spu->spu }}</td>
            <td>{{ $spu->spu }}</td>
            <td>{{ $spu->updated_at }}</td>
            <td>{{ $spu->created_at }}</td>
            <td>
                <a href="{{ route('spu.show', ['id'=>$spu->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 查看
                </a>
                <a href="{{ route('spu.edit', ['id'=>$spu->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
                </a>
                <a href="{{ route('createSpuImage', ['spu_id'=>$spu->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑图片
                </a>
                <a href="javascript:" class="btn btn-danger btn-xs delete_item"
                   data-id="{{ $spu->id }}"
                   data-url="{{ route('spu.destroy', ['id' => $spu->id]) }}">
                    <span class="glyphicon glyphicon-trash"></span> 删除
                </a>
            </td>
        </tr>
    @endforeach

    @section('doAction')
        <div class="row">
            <div class="col-lg-12">
                <button class="doAction" value="image_edit">批量已制图</button>
                <button>批量退回</button>
                <button>批量已编辑</button>
                <button>批量转采购</button>
                <select>
                    <option>==采购==</option>
                    @foreach($users as $user)
                        
                        <option value='{{$user->id}}'>{{$user->name}}</option>
                    @endforeach
                </select>

                <button type="button" class="dispatch"  value="image_edit">批量转美工</button>
                <select class="select">
                    <option>==美工==</option>
                    @foreach($users as $user)
                        <option value='{{$user->id}}'>{{$user->name}}</option>
                    @endforeach
                </select>

                <button class='dispatch' value="edit_user">批量转编辑</button>
                <select>
                    <option>==编辑==</option>
                    @foreach($users as $user)
                    
                        <option value='{{$user->id}}'>{{$user->name}}</option>
                    @endforeach
                </select>

                <button>批量转开发</button>
                <select>
                    <option>==开发==</option>
                    @foreach($users as $user)
                        
                        <option value='{{$user->id}}'>{{$user->name}}</option>
                    @endforeach
                </select>

            </div>
        </div>
    @stop
    <br>
@stop

@section('childJs')
    <script type="text/javascript">
        //全选
        function quanxuan() {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }

        $('.dispatch').click(function () {
            //console.log($(this).next().find("option:selected").val());
            var user_id = $(this).next().find("option:selected").val();
            var action = $(this).val();
            var url = "{{route('dispatchUser')}}";

            var checkbox = document.getElementsByName("tribute_id");
            var spu_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                spu_ids += checkbox[i].value + ",";
            }
            spu_ids = spu_ids.substr(0, (spu_ids.length) - 1);
            $.ajax({
                url: url,
                data: {user_id: user_id, action: action,spu_ids:spu_ids},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    window.location.reload();
                }
            })
        });

        $('.doAction').click(function () {
            //console.log($(this).next().find("option:selected").val());
            //var user_id = $(this).next().find("option:selected").val();
            var action = $(this).val();
            var url = "{{route('doAction')}}";

            var checkbox = document.getElementsByName("tribute_id");
            var spu_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                spu_ids += checkbox[i].value + ",";
            }
            spu_ids = spu_ids.substr(0, (spu_ids.length) - 1);
            $.ajax({
                url: url,
                data: {action: action,spu_ids:spu_ids},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    window.location.reload();
                }
            })
        });
    </script>
@stop
