@extends('mooc.layouts.main')

@section('title', '開放式課程討論區-課程內容')

@section('head-extension')
    <script>
        function updateMessage(id){            
            $.ajax({
                type: "put",
                datatype: "json",
                url: "/api/message/update",
                data: $("#updateMessageForm_" + id).serialize(),
                success:function(response){
                    location.reload();
                },
                error: function (response) {
                    var data = $.parseJSON(response.responseText);
                    $("#updateMessageError_" + id).text(data.errMsg);
                },
            });
        };

        function deleteMessage(id){            
            $.ajax({
                type: "delete",
                datatype: "json",
                url: "/api/message/delete",
                data: {"_token": "{{ csrf_token() }}", "id": id},
                success:function(response){
                    location.reload();
                },
                error: function (response) {                    
                    var data = $.parseJSON(response.responseText);                    
                    $("#deleteMessageError_" + id).text(data.errMsg);
                },
            });
        };
    </script>
@endsection

@section('content')
    <div class="container mt-5 position-relative">

    <!-- 課程描述 -->
        <div class="jumbotron">
            <h2 class="text-center">{{ $classes->first()->className }}</h2>
            <p class="font-italic text-center">{{ $classes->first()->teacher }}</p>
            <hr class="my-4">
            <p>{{ $classes->first()->description }}</p>
            <p class="lead">
                <a class="badge badge-pill badge-info" href="#" role="button">前往資料來源網站</a>
            </p>
        </div>


    <!-- 章節 -->    
        <table class="table table-striped">
            <thead>
                <tr>        
                    <th scope="col">章節</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($titles as $title)
                    <tr>            
                        <td><a href="{{ $title->videoLink }}" target=blank >{{ $title->title }}</a></td>
                    </tr>
                @endforeach        
            </tbody>
        </table>

    <!-- 章節頁數 -->
        <div class="mt-3 mx-auto">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    {{ $titles->appends(['class' => $classes->first()->classId])->links() }}
                </ul>
            </nav>
        </div>    

    <!-- 留言板 --> 
        <h3 class="mb-3">留言板:</h3>    
        @foreach ($messages as $message)
            <div class=" mb-3 mx-auto col-sm-12 col-lg-10">
                <div class="card">
                    <div class="card-body">                        
                            <h5 class="card-title text-info">{{ $message->userName }}</h5>
                            
                            <!-- 修改留言下拉選單 -->
                            @if (Auth::check())
                                @if (Auth::user()->name == $message->userName)
                                    <div class="dropdown position-absolute " style="top:0.5em;right:0.5em;">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        
                                    </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" data-toggle="modal" data-target="#update_message_box_{{ $message->id }}">修改</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#delete_message_box_{{ $message->id }}">刪除</a>
                                        </div>
                                    </div>

                                    <!-- 彈出修改留言input box -->
                                    <div class="modal fade" id="update_message_box_{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="messageDropdownList" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="messageDropdownList">修改留言</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form method="post" id="updateMessageForm_{{ $message->id }}">
                                                @csrf
                                                <input type="hidden" name="_method" value="put">
                                                <input type="hidden" name="id" value="{{ $message->id }}">
                                                <div class="modal-body">
                                                    <textarea class="form-control bg-light" id="updateMessageText_{{ $message->id }}" name="message" rows="6">{{ $message->message }}</textarea>
                                                </div>
                                                <div class="modal-footer">
                                                <p class="my-auto mx-auto text-danger" id="updateMessageError_{{ $message->id }}"></p>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                                                    <button type="button" class="btn btn-success" onclick="updateMessage({{ $message->id }})">送出</button>
                                                </div>                                                
                                            </form>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- 彈出刪除留言確認 box -->
                                    <div class="modal fade" id="delete_message_box_{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="messageDropdownList" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="messageDropdownList">確認刪除留言?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form method="post" id="updateMessageForm_{{ $message->id }}">
                                                @csrf
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="hidden" name="id" value="{{ $message->id }}">
                                                
                                                <div class="modal-footer">
                                                <p class="my-auto mx-auto text-danger" id="deleteMessageError_{{ $message->id }}"></p>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                                                    <button type="button" class="btn btn-danger" onclick="deleteMessage({{ $message->id }})">確認</button>
                                                </div>                                                
                                            </form>

                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        
                            <p class="card-text pl-3 text-muted">{{ $message->message }}</p>
                        
                    </div>
                </div>
            </div>
        @endforeach
        {{ $messages->appends(['class' => $classes->first()->classId])->links() }}
    <!--留言表單-->
        <div class="pb-5 mb-5 mx-auto col-sm-12 col-lg-10">
            <div class="card">                
                <div class="card-body">
                    <h5 class="card-title text-info">Name</h5>
                    <form method="post" action="/message/create">
                    @csrf
                        <div class="form-group">                        
                            <textarea class="form-control bg-light" name="message" rows="5"></textarea>
                            <button type="submit" class="btn btn-primary mt-2 float-right">送出</button>
                        </div>
                        <input type="hidden" name="classId" value="{{ $classes->first()->classId }}">
                    </form>  
                </div>
            </div>
        </div>

    </div>

@endsection