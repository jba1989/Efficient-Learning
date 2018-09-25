<!DOCTYPE HTML>

<html>
	<head>
		<title>開放式課程討論區-@yield('title')</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="{{ asset('css/mooc/main.css') }}" />
        <style>
            @yield('css')
        </style>
	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<nav class="left">
					<a href="#menu"><span>Menu</span></a>
				</nav>
				<a href="/index" class="logo">intensify</a>
				<nav class="right">
					<a href="#" class="button alt">Log in</a>
				</nav>
			</header>

		<!-- Menu -->
			<nav id="menu">
				<ul class="links">
					<li><a href="/index">首頁</a></li>
					<li><a href="/member">會員資料</a></li>
					<li><a href="/favorite">我的最愛</a></li>
					<li><a href="/school/ntu">台大課程總覽</a></li>
				</ul>
				<ul class="actions vertical">
					<li><a href="#" class="button fit">Login</a></li>
				</ul>
			</nav>

		<!-- Banner -->
			@yield('banner')

        <!-- Content -->
            <div>
                @yield('content')
            </div>

		<!-- Footer -->
			<footer id="footer">
				<div class="inner">
					<h2>Get In Touch</h2>
					<ul class="actions">
						<li><span class="icon fa-phone"></span> <a href="#">(000) 000-0000</a></li>
						<li><span class="icon fa-envelope"></span> <a href="#">information@untitled.tld</a></li>
						<li><span class="icon fa-map-marker"></span> 123 Somewhere Road, Nashville, TN 00000</li>
					</ul>
				</div>
				<div class="copyright">
					<p style="margin:0;">本站資料來源</p>
					<p style="margin:0;"><a href="http://ocw.aca.ntu.edu.tw/ntu-ocw/"/>台大開放式課程 http://ocw.aca.ntu.edu.tw/ntu-ocw/</p>
					<p style="margin:0;"><a href="http://ocw.nctu.edu.tw/"/>交大開放式課程 http://ocw.nctu.edu.tw/</p>
					<p style="margin:0;"><a href="http://ocw.nthu.edu.tw/"/>清大開放式課程 http://ocw.nthu.edu.tw/</p>
				</div>
			</footer>

		<!-- Scripts -->
			<script src="{{ asset('js/mooc/jquery.min.js') }}"></script>
			<script src="{{ asset('js/mooc/jquery.scrolly.min.js') }}"></script>
			<script src="{{ asset('js/mooc/skel.min.js') }}"></script>
			<script src="{{ asset('js/mooc/util.js') }}"></script>
			<script src="{{ asset('js/mooc/main.js') }}"></script>

	</body>
</html>