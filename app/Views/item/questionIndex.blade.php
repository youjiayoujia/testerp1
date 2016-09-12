@extends('common.table')
@section('tableToolButtons')
    
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th>提问对象</th>
    <th>图片</th>
    <th>提问</th>
    <th>提问日期</th>
    <th>提问人</th>
    <th>解答</th>
    <th>解答日期</th>
    <th>解答人</th>
    <th>追加问题</th>
    <th>状态</th>
    <th>操作</th>
@stop

@section('tableBody')
    @foreach($data as $question)
        <tr>
            <td>{{config('product.question.types')[$question->question_group]}}</td>
            <td><img src="{{ asset($question->image) }}" width="100px"></td>
            <td>{{$question->question}}</td>
            <td>{{$question->question_time}}</td>
            <td>{{$question->questionUser?$question->questionUser->name:''}}</td>
            <td>{{$question->answer}}</td>
            <td>{{$question->answer_date}}</td>
            <td>{{$question->answerUser?$question->answerUser->name:''}}</td>
            <td>{{$question->extra_question}}</td>
            <td>{{$question->status}}</td>
            <td>
                <a data-toggle="modal" data-target="#question_{{$question->sku_id}}" title="追加提问" class="btn btn-warning btn-xs" id="">
                    <span class="glyphicon glyphicon glyphicon-pencil"></span>
                </a>
                <a data-toggle="modal" data-target="#answer_{{$question->sku_id}}" title="解答" class="btn btn-info btn-xs" id="">
                    <span class="glyphicon glyphicon-question-sign"></span>
                </a>
            </td>
        </tr>

        <!-- 模态框（Modal）追加提问 -->
        <form action="/item/extraQuestion" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="question_{{$question->sku_id}}"  role="dialog" 
               aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" 
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           常见问题提问
                        </h4>
                     </div>

                     <div class="modal-body">
                        

                        <div>向
                            <select name='question_group'>
                                @foreach(config('product.question.types') as $key=>$value)
                                    <option value="{{$key}}" {{ $question->question_group == $key ? 'selected' : '' }}>{{$value}}</option>
                                @endforeach
                            </select>
                        分组提问</div>
                        <br>
                        <div><textarea rows="3" cols="88" name='question_content'>{{$question->question}}</textarea></div>

                     </div>

                     <div class="modal-body">
                        

                        <div>解答</div>
                        <br>
                        <div><textarea rows="3" cols="88" name='question_content'>{{$question->answer}}</textarea></div>
                        
                     </div>

                     <div class="modal-body">
                        <div>追加内容</div>
                        <br>
                        <div><textarea rows="3" cols="88" name='extra_content'>{{$question->extra_question}}</textarea></div>
                     </div>
                     <input type='hidden' value='{{$question->id}}' name='id'>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                           data-dismiss="modal">关闭
                        </button>
                        <button type="submit" class="btn btn-primary" name='edit_status' value='image_unedited'>
                           提交
                        </button>
                     </div>
                  </div>
            </div>
            </div>
        </form>
        <!-- 模态框结束（Modal） -->

        <!-- 模态框（Modal）解答 -->
        <form action="/item/answer" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="answer_{{$question->sku_id}}"  role="dialog" 
               aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" 
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           解答
                        </h4>
                     </div>

                     <div class="modal-body">
                        <div><textarea rows="3" cols="88" name='answer_content'></textarea></div>
                        <div style="color:red">请尽可能详尽的解答所提出的疑问。</div>
                     </div>
                     <input type='hidden' value='{{$question->id}}' name='id'>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                           data-dismiss="modal">关闭
                        </button>
                        <button type="submit" class="btn btn-primary" name='edit_status' value='image_unedited'>
                           提交
                        </button>
                     </div>
                  </div>
            </div>
            </div>
        </form>
        <!-- 模态框结束（Modal） -->

    @endforeach
@stop

@section('childJs')
    
@stop