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

    <!-- classType -->
    <section id="three" class="wrapper">
        <div class="inner bg-orange flex flex-3">
            <div class="flex-item box">
                <div class="image fit">
                    <a href="NTU_class.php?classtype=熱門點閱"><img src="images/type1.png" alt="" /></a>
                </div>
                <div class="content">
                    <h3><a href="NTU_class.php?classtype=熱門點閱">熱門點閱</a></h3>
                    <p style="color:red;">最新功能!!!</p>
                </div>
            </div>
            <div class="flex-item box">
                <div class="image fit">
                    <a href="NTU_class.php?classtype=理工電資"><img src="images/type3.png" alt="" /></a>
                </div>
                <div class="content">
                    <h3><a href="NTU_class.php?classtype=理工電資">理工電資</a></h3>
                    <p></p>
                </div>
            </div>
            <div class="flex-item box">
                <div class="image fit">
                    <a href="NTU_class.php?classtype=法社管理"><img src="images/type2.png" alt="" /></a>
                </div>
                <div class="content">
                    <h3><a href="NTU_class.php?classtype=法社管理">法社管理</a></h3>
                    <p></p>
                </div>
            </div>
        </div>
    </section>

    <!-- 課程表 -->
    <section id="main" class="wrapper">
        <div class="inner">
            <h3>課程表</h3>
            <div class="table-wrapper">
                <table id="classList">
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

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection