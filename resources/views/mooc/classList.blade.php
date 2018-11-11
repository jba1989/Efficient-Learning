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
    <div class="container my-5 ">

    <!-- 分類選單 -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link 
                @isset ($classType)
                    @if ($classType == '1') 
                        active
                    @endif
                @endisset
                " href="1">熱門課程
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>        
        </ul>

    <!-- 課程表 -->
        <div class="my-5">
            <table class="table table-striped">                
                <thead>
                <tr>
                    <th class="text-right">課程ID:</th>
                    <th class="text-center">課程名稱:</th>
                    <th class="text-center">讚數:</th>
                    <th class="text-center">開課教授:</th>
                    <th class="text-center">課程分類:</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($classes as $class)
                <tr>
                    <td class="text-right">{{ $class->classId }}</td>
                    <td class="pl-3"><a href="/class/{{ $class->classId }}" style="display:block;">{{ $class->className }}</a></td>
                    <td class="text-center">{{ 1 }}</td>
                    <td class="text-center">{{ $class->teacher }}</td>
                    <td>{{ $class->type }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    <!-- 課程頁數 -->
        <div class="w-100">
            <div class="mx-auto">
                @if (isset($school) || isset($type))
                    {{ $classes->appends(['school' => $school, 'type' => $type])->links() }}
                @else
                    {{ $classes->links() }}
                @endif
            </div>
        </div>

    </div>

@endsection