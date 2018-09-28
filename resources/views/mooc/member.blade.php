@extends('layouts.main')

@section('title', 'member')

@section('css')
    ul, li {
        display:inline;
        text-align:center;
    }
@endsection

@section('content')
    <section id="main" class="wrapper">

        <div class="inner">
            <header class="align-center">
                <h1>會員資料</h1>
            </header>

            <div style="position:relative; left:40%;">
                <img src="" alt="會員頭像" style="width:20%;"/>
            </div>
            <form action="imageRevise.php" method="post" enctype="multipart/form-data">
                <ul style="margin-left:28%">
                    <li><input type="file" name="fileUpload" /></li>
                    <li><input type="submit" style="background-color:none; line-height:0; width:5em; height:1.8em; margin:0; padding:0; text-align:center;" value="更換頭像" /></li>
                </ul>
            </form>

            <table style="padding-bottom:1em;">
                <tr>
                    <td style="text-align:center">會員ID :</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:center">Email:</td>
                    <td></td>
                </tr>
            </table>

            <div ><a href="memberRevise.php" class="button icon fa-download" style="width:33%; margin:3em 33%;" />修改資料</a></div>

        </div>
    </section>
@endsection