@extends('mooc.layouts.main')

@section('title', '開放式課程討論區-課程內容')

@section('script-extension')
    @parent
@endsection

@section('banner')    
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
                    {{ $titles->links() }}
                </ul>
            </nav>
        </div>    

    <!-- 留言板 --> 
        <h3 class="mb-3">留言板:</h3>    
        @foreach ($messages as $message)
            <div class=" mb-3 mx-auto col-sm-12 col-lg-10">
                <div class="card" style="overflow:scroll;overflow-X:hidden;height:10em;">
                    <div class="card-body">
                        <h5 class="card-title text-info">{{ $message->userName }}</h5>
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