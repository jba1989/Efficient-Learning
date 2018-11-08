@extends('mooc.layouts.main')

@section('title', 'member')

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
<div class="container">
    <div class="card-deck mt-5">
        <div class="card">
            <img class="card-img-top" src="{{ asset('images/NTU_logo.jpg') }}" alt="台大logo">
            <div class="card-body">
            <h5 class="card-title">台大開放式課程</h5>
            <p class="card-text"></p>
            </div>
            <div class="card-footer">
            <small class="text-muted">台大開放式課程</small>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('images/NTHU_logo.png') }}" alt="清大logo">
            <div class="card-body">
            <h5 class="card-title">清大開放式課程</h5>
            <p class="card-text"></p>
            </div>
            <div class="card-footer">
            <small class="text-muted">清大開放式課程</small>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('images/NCTU_logo.png') }}" alt="交大logo">
            <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text"></p>
            </div>
            <div class="card-footer">
            <small class="text-muted">交大開放式課程</small>
            </div>
        </div>
    </div>
</div>
@endsection