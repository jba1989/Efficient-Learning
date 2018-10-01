@extends('layouts.main')

@section('title', 'member')

@section('css')
    ul, li {
        display:inline;
        text-align:center;
    }
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
    <section id="three" class="wrapper">
        <div class="inner flex flex-3">
            <div class="flex-item box">
                <div class="image fit">
                    <a href="/school/ntu"><img src="{{ asset('images/NTU_logo.jpg') }}" alt="" /></a>
                </div>
                <div class="content">
                    <h3><a href="NTU_class.php">台大課程</a></h3>
                    <p>全新網頁,火熱開放中</p>
                </div>
            </div>

            <div class="flex-item box">
                <div class="image fit">
                    <a href="#"><img src="{{ asset('images/NTHU_logo.png') }}" alt="" /></a>
                </div>
                <div class="content">
                    <h3>清大課程</h3>
                    <p style="color:red">尚未開放,敬請期待</p>
                </div>
            </div>

            <div class="flex-item box">
                <div class="image fit">
                    <a href="#"><img src="{{ asset('images/NCTU_logo.jpg') }}" alt="" /></a>
                </div>
                <div class="content">
                    <h3>交大課程</h3>
                    <p style="color:red">尚未開放,敬請期待</p>
                </div>
            </div>
        </div>
    </section>
@endsection