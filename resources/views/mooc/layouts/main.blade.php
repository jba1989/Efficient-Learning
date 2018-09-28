<!DOCTYPE HTML>
<!--
	Intensify by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>        
		<title> @yield('title')</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="{{ asset('css/mooc/main.css') }}" />       
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
					<li><a href="/index">Home</a></li>
					<li><a href="/generic">Generic</a></li>
					<li><a href="/elements">Elements</a></li>
				</ul>
				<ul class="actions vertical">
					<li><a href="#" class="button fit">Login</a></li>
				</ul>
			</nav>

        <!-- Content -->
            <div class="container">
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
					&copy; Untitled. Design <a href="https://templated.co">TEMPLATED</a>. Images <a href="https://unsplash.com">Unsplash</a>.
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