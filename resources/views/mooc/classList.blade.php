@extends('mooc.layouts.main')

@section('title', '開放式課程討論區-課程清單')

@section('css')
@endsection

@section('banner')
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="{{ asset('images/banner.jpg') }}" alt="First slide">
            </div>				
        </div>
    </div>
@endsection

@section('content')

    
    

    <!-- 課程表 -->
    
    <div class="container mt-5">
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

        <h3>課程表</h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th style="text-align:center">課程ID:</th>
                <th>課程名稱:</th>
                <th style="text-align:center">讚數:</th>
                <th>開課教授:</th>
                <th>課程分類:</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $class)
            <tr>
                <td>{{ $class->classId }}</td>
                <td><a href="/class/{{ $class->classId }}" style="display:block;">{{ $class->className }}</a></td>
                <td>{{ 1 }}</td>
                <td>{{ $class->teacher }}</td>
                <td>{{ $class->type }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>               
        {{ $data->links() }}
    </div>

@endsection