@extends('mooc.layouts.main')

@section('title', '開放式課程討論區-課程內容')

@section('css')
    
@endsection

@section('banner')
    <section id="banner">
        <div class="content">
            <h1>開放式課程討論區</h1>
            <p><br />一起增加學習效率吧!</p>
            <ul class="actions">
                <li><a href="#one" class="button scrolly">Get Started</a></li>
            </ul>
        </div>
    </section>
@endsection

@section('content')
    <!--課程表 -->
    <div class="inner">
        <div class="table-wrapper">
            <table id="classList" style="width:90%">
                <caption><h3>課程表</h3></caption>
                <tbody>                    
                    <!--由資料庫撈取的data-->
                    @foreach ($data as $class)
                        <tr>
                            <td style="width=100%; padding-left:10%; padding-right:10%; white-space:nowrap">
                                <a href="{{ $class->videoLink }}" target=blank >{{ $class->title }}</a>
                            </td>
                        </tr>                    
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $data->links() }}
    </div>

    <!-- 留言板 -->
    <div class="inner" id="board">
        <div class="12u$">
            <h3 >留言板:</h3>                 
            <div style="margin: 2em 0 0 0;">
                <code><b style="font-size:1.5em; padding-right:0.5em">
                    123
                </code>
            </div>            

            <!--留言表單-->
            <form method="post" action="#">
                <div class="12u$">
                    <textarea name="message" id="message" maxlength="300" placeholder="最大長度300字" rows="6" ></textarea>
                </div>
                
                <div class="12u$">
                    
                    <ul class="actions">
                        <li><input type="submit" name="submit" value="Send Message" /></li>
                        <li><input type="reset" name="reset" value="Reset" class="alt" /></li>
                    </ul>
                    
                </div>
            </form>                    
        </div>
    </div>
@endsection