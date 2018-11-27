@extends('mooc.layouts.main')

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
                }
            });
        };

        function updateLikeCount(classId){            
            $.ajax({
                type: "get",
                datatype: "json",
                url: "/api/class/like",
                data: {"_token": "{{ csrf_token() }}", "classId": "{{ $classes->classId }}"},
                success: function(response){
                    location.reload();
                }
            });
        };
    </script>

    <style>
        #like_img, #dislike_img {
            height: 50px;
            width: 50px;
            margin: 10px;
        };
    </style>    
@endsection

@section('script-extension')
        $.ajax({
            type: "get",
            datatype: "json",
            url: "/api/class/like",
            data: {"_token": "{{ csrf_token() }}", "classId": "{{ $classes->classId }}"},
            success:function(response) {
                $("#like_count").text(response.data.likeCount);
                $("#dislike_count").text(response.data.dislikeCount);
                switch (response.data.prefer) {
                    case "like":
                        $("#like_img").attr("src", "{{ asset('images/like3.png') }}");
                        break;
                    case "dislike":
                        $("#dislike_img").attr("src", "{{ asset('images/like3.png') }}");
                        break;
                    default:
                        $("#like_img").attr("src", "{{ asset('images/like2.png') }}");
                        $("#dislike_img").attr("src", "{{ asset('images/like2.png') }}");
                        break;
                }
            }
        });

        $("img").click(function(){
            var prefer = $(this).attr("alt");
            $.ajax({
                type: "put",
                datatype: "json",
                url: "/api/class/like",
                data: {"_token": "{{ csrf_token() }}", "classId": "{{ $classes->classId }}", "prefer": prefer},
                success: function(response){                    
                    switch (response.data.prefer) {
                    case "like":
                        $("#like_img").attr("src", "{{ asset('images/like3.png') }}");
                        $("#dislike_img").attr("src", "{{ asset('images/like2.png') }}");
                        break;
                    case "dislike":
                        $("#like_img").attr("src", "{{ asset('images/like2.png') }}");
                        $("#dislike_img").attr("src", "{{ asset('images/like3.png') }}");
                        break;
                    default:
                        $("#like_img").attr("src", "{{ asset('images/like2.png') }}");
                        $("#dislike_img").attr("src", "{{ asset('images/like2.png') }}");
                        break;
                    }
                    $("#like_count").text(response.data.likeCount);
                    $("#dislike_count").text(response.data.dislikeCount);
                }
                
            });
        });
        
        
@endsection

@section('content')
    <div class="container mt-5">

    <!-- 課程描述 -->
        <div class="jumbotron pb-1">
            <h2 class="text-center">{{ $classes->className }}</h2>
            <p class="font-italic text-center">{{ $classes->teacher }}</p>
            <hr class="my-4">
            <p>{{ $classes->description }}</p>
            <p class="lead">123456 </p>
            <p class="text-center mt-2"><a class="badge badge-pill badge-info" href="#" role="button">{{ __('dictionary.ResourceURL') }}</a></p>
            
            <!-- 讚按鈕 -->
            <table class="mx-auto">
                <tr>
                    <td class="text-center"><img id="like_img" src="{{ asset('images/like2.png') }}" alt="like"></td>
                    <td class="text-center"><img id="dislike_img" class="ml-2" src="{{ asset('images/like2.png') }}" alt="dislike"></td>
                </tr>
                <tr>
                    <td class="text-center"><p id="like_count"></p></td>
                    <td class="text-center"><p id="dislike_count"></p></td>
                </tr>
            </table>            
        </div>

    <!-- 章節 -->    
        <table class="table table-striped">
            <thead>
                <tr>        
                    <th scope="col">{{ __('dictionary.Title') }}</th>
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
                    {{ $titles->appends(['class' => $classes->classId])->links() }}
                </ul>
            </nav>
        </div>    

    <!-- 留言板 --> 
        <h3 class="mb-3">{{ __('dictionary.Message Board') }}:</h3>    
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
                                            <a class="dropdown-item" data-toggle="modal" data-target="#update_message_box_{{ $message->id }}">{{ __('dictionary.Edit') }}</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#delete_message_box_{{ $message->id }}">{{ __('dictionary.Delete') }}</a>
                                        </div>
                                    </div>

                                    <!-- 彈出修改留言input box -->
                                    <div class="modal fade" id="update_message_box_{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="messageDropdownList" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="messageDropdownList">{{ __('dictionary.Edit Message') }}</h5>
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
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('dictionary.Cancel') }}</button>
                                                    <button type="button" class="btn btn-success" onclick="updateMessage({{ $message->id }})">{{ __('dictionary.Send') }}</button>
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
                                                <h5 class="modal-title" id="messageDropdownList">{{ __('dictionary.Delete Confirm') }}</h5>
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
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('dictionary.Cancel') }}</button>
                                                    <button type="button" class="btn btn-danger" onclick="deleteMessage({{ $message->id }})">{{ __('dictionary.Submit') }}</button>
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
                    @if (Auth::check())
                        <h5 class="card-title text-info">{{ Auth::user()->name }}</h5>
                    @else
                        <h5 class="card-title text-info">{{ __('auth.Guest') }}</h5>
                    @endif
                    <form method="post" action="/message/create">
                    @csrf
                        <div class="form-group">                        
                            <textarea class="form-control bg-light" name="message" rows="5"></textarea>
                            <button type="submit" class="btn btn-primary mt-2 float-right">{{ __('dictionary.Submit') }}</button>
                        </div>
                        <input type="hidden" name="classId" value="{{ $classes->first()->classId }}">
                    </form>  
                </div>
            </div>
        </div>

    </div>

@endsection