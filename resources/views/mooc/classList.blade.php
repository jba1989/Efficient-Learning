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
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">Active</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled" href="#">Disabled</a>
        </li>
    </ul>

@endsection