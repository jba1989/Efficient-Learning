@extends('mooc.layouts.main')

@section('title', '開放式課程討論區-課程內容')

@section('css')
    
@endsection

@section('banner')    
@endsection

@section('content')
    <!--課程表 -->
    <div class="container">
    <table class="table table-striped">
    <thead>
        <tr>        
            <th scope="col">章節</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $class)
            <tr>            
                <td><a href="{{ $class->videoLink }}" target=blank >{{ $class->title }}</a></td>
            </tr>
        @endforeach        
    </tbody>
    </table>   

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            {{ $data->links() }}
        </ul>
    </nav>
    

    <!-- 留言板 -->
    <div class="inner" id="board">
        <div class="12u$">
            <h3 >留言板:</h3>    
            @foreach ($message as $content)             
            <div style="margin: 2em 0 0 0;">
                <code>
                    {{ $content->userName }}
                    {{ $content->message }}
                </code>
            </div>            
            @endforeach
            <!--留言表單-->
            <form method="post" action="/message/store">
            {{ csrf_field() }}
                <div class="12u$">
                    <textarea name="message" id="message" maxlength="300" placeholder="最大長度300字" rows="6" ></textarea>
                </div>
                
                <div class="12u$">                    
                    <ul class="actions">
                        <li><input type="submit" name="submit" value="Send Message" /></li>
                        <li><input type="reset" name="reset" value="Reset" class="alt" /></li>
                    </ul>
                    <input type="hidden" name="classId" value="{{ $class->classId }}" />                 
                </div>
            </form>                    
        </div>
    </div>
    </div>
@endsection