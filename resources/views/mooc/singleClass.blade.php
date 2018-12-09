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
                    $(".modal").modal('hide');
                    $("#message_" + id).text(response.message);
                },
                error: function (response) {
                    var data = response.responseJSON.errors.message;
                    $("#updateMessageError_" + id).text(data[0]);
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
                    $(".modal").modal('hide');
                    $("#message_" + id).parents("div.card").hide();
                },
                error: function (response) {                    
                    var data = response.responseJSON.errors.message;
                    $("#deleteMessageError_" + id).text(data[0]);
                }
            });
        };
    </script>

    <style>
        #like_img, #dislike_img, #love_img {
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

        $(".like").click(function(){
            var prefer = $(this).attr("alt");
            $.ajax({
                type: "put",
                datatype: "json",
                url: "/api/class/like",
                data: {"_token": "{{ csrf_token() }}", "classId": "{{ $classes->classId }}", "prefer": prefer},
                success: function(response){                    
                    switch (response.data.prefer) {
                    case "like":
                        $("#like_img").attr("src", "{{ asset('images/like2.png') }}");
                        $("#dislike_img").attr("src", "{{ asset('images/dislike1.png') }}");
                        break;
                    case "dislike":
                        $("#like_img").attr("src", "{{ asset('images/dislike2.png') }}");
                        $("#dislike_img").attr("src", "{{ asset('images/like1.png') }}");
                        break;
                    default:
                        $("#like_img").attr("src", "{{ asset('images/like1.png') }}");
                        $("#dislike_img").attr("src", "{{ asset('images/like1.png') }}");
                        break;
                    }
                    $("#like_count").text(response.data.likeCount);
                    $("#dislike_count").text(response.data.dislikeCount);
                },
                error: function(response){
                    var data = response.responseJSON.errors.message;
                    $(".alert").find("strong").text(data[0]);
                    $(".alert").removeClass("d-none");
                }                
            });
        });

        $.ajax({
            type: "get",
            datatype: "json",
            url: "/api/user/show",
            data: {"_token": "{{ csrf_token() }}", "classId": "{{ $classes->classId }}"},
            success:function(response) {
                if (response.data.favorite) {
                    $("#love_img").attr("src", "{{ asset('images/love2.png') }}");
                }
            }
        });

        $(".love").click(function(){
            $.ajax({
                type: "put",
                datatype: "json",
                url: "/api/user/update",
                data: {"_token": "{{ csrf_token() }}", "classId": "{{ $classes->classId }}"},
                success: function(response){
                    if (response.data.favorite) {
                        $("#love_img").attr("src", "{{ asset('images/love2.png') }}");
                    } else {
                        $("#love_img").attr("src", "{{ asset('images/love1.png') }}");
                    }
                },
                error: function(response){
                    var data = response.responseJSON.errors.message;
                    $(".alert").find("strong").text(data[0]);
                    $(".alert").removeClass("d-none");
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
            @isset ($classes->description)
                @foreach ($classes->description as $description)
                    <p>{{ $description }}</p>
                @endforeach  
            @endisset   
            <p class="text-center mt-2"><a class="badge badge-pill badge-info" href="#" role="button">{{ __('dictionary.ResourceURL') }}</a></p>
            
            <!-- 讚按鈕 -->
            <table class="mx-auto">
                <tr>
                    <td class="text-center"><img id="like_img" class="like" src="{{ asset('images/like1.png') }}" alt="like"></td>
                    <td class="text-center"><img id="dislike_img" class="like" src="{{ asset('images/dislike1.png') }}" alt="dislike"></td>
                    <td class="text-center"><img id="love_img" class="love" src="{{ asset('images/love1.png') }}" alt="love"></td>
                </tr>
                <tr>
                    <td class="text-center"><p id="like_count"></p></td>
                    <td class="text-center"><p id="dislike_count"></p></td>
                    <td></td>
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
            <nav class="mx-auto">
                <ul class="pagination">
                    {{ $titles->appends(['class' => $classes->classId, 'page' => $page, 'msg_page' => $msg_page])->links() }}
                </ul>
            </nav>
        </div>    

    <!-- 留言板 --> 
        <h3 class="mb-3">{{ __('dictionary.Message Board') }}:</h3>    
        @foreach ($messages as $message)            
            <div class="card mb-3 pb-1 mx-auto col-sm-12 col-lg-10">
                <div class="card-body">                        
                    <h4 class="card-title text-info">{{ $message->userName }}</h4>
                    
                    <!-- 修改留言下拉選單 -->
                    @if (Auth::check())
                        @if (Auth::user()->name == $message->userName)
                            <div class="dropdown position-absolute " style="top:0.5em;right:0.5em;">
                            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                
                            </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#updateMessageBox_{{ $message->id }}">{{ __('dictionary.Edit') }}</a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#deleteMessageBox_{{ $message->id }}">{{ __('dictionary.Delete') }}</a>
                                </div>
                            </div>

                            <!-- 彈出修改留言input box -->
                            <div class="modal fade" id="updateMessageBox_{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="messageDropdownList" aria-hidden="true">
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
                            <div class="modal fade" id="deleteMessageBox_{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="messageDropdownList" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="messageDropdownList">{{ __('dictionary.Delete Confirm') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form method="post" id="deleteMessageForm_{{ $message->id }}">
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
                    <pre class="px-3" style="min-height: 3em;"><code class="card-text text-muted" id="message_{{ $message->id }}">{{ $message->message }}</code></pre>
                </div>                
            </div>
        @endforeach
        {{ $messages->appends(['class' => $classes->classId, 'page' => $page, 'msg_page' => $msg_page])->links() }}
    
    <!--留言表單-->        
        <div class="card mb-5 mx-auto col-sm-12 col-lg-10">                
            <div class="card-body">                
                @if (Auth::check())
                    <h4 class="card-title text-info">{{ Auth::user()->name }}</h4>
                @else
                    <h4 class="card-title text-info">{{ __('auth.Guest') }}</h4>
                @endif
                <form method="post" action="/message/create">
                @csrf
                    <div class="form-group">
                        <textarea class="form-control bg-light" name="message" rows="5">{{ old('message') }}</textarea>
                        <button type="submit" class="btn btn-primary mt-2 float-right">{{ __('dictionary.Submit') }}</button>
                    </div>
                    <input type="hidden" name="classId" value="{{ $classes->classId }}">
                </form>  
            </div>
        </div>

    </div>

@endsection