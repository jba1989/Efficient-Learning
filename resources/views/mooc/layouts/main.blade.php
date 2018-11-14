<!DOCTYPE HTML>

<html>
	<head>
		<title>開放式課程討論區-@yield('title')</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		            
		<script>
			$(document).ready(function() {
                $("#searchClass").find("input").focus(function() {
                    $("#searchClass").css("width","100%");
                });
                $("#searchClass").find("input").blur(function() {
                    $("#searchClass").css("width","15em");
                });

                $("#findClass").click(function() {
                    var classId = $("#searchClass").find("input").val();
                    window.location.assign("{{ route('class') }}?class=" + classId);
                });

			 	@yield('script-extension')   
			});
    	</script>

	</head>
	<body>
		<!-- Nav -->
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<a class="navbar-brand" href="#">Carousel</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
        	<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Link</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Disabled</a>
					</li>
					<li class="nav-item mr-5">
						<div class="btn-group">
							<button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								各校開放式課程
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="{{ route('class') }}?school=ntu">台大</a>
								<a class="dropdown-item" href="{{ route('class') }}?school=ntu">清大</a>
								<a class="dropdown-item" href="{{ route('class') }}?school=nctu">交大</a>
							</div>						
						</div>
					</li>					
				</ul>

		<!-- 課程搜尋input框 -->
				<div class="input-group mr-2" id="searchClass" style="width:15em;">
					<input type="text" id="searchClass" class="form-control" list="classList" placeholder="快速尋找課程">
					<datalist id="classList">
						@isset ($classOptions)
							@foreach ($classOptions as $classOption)
								<option value="{{ $classOption->classId }}">{{ $classOption->classId }} - {{ $classOption->className }}</option>
							@endforeach
						@endisset
					</datalist>
					<div class="input-group-append">
						<button id="findClass" class="btn btn-outline-info" type="button">確認</button>
					</div>
				</div>

		<!-- 登入等按鈕 -->
				<ul class="navbar-nav mr-2em">
					<li class="nav-item">
						<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
					</li>					
				</ul>			
        	</div>
      	</nav>
		<div style="height:56px;"></div>

		<!-- Error message -->
		@if (isset($errors))
		<div class="container mt-5">
			@foreach (($errors->all()) as $message)
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				<strong>{{ $message }}</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">					
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@endforeach
		</div>
		@endif

		<!-- Banner -->
			@yield('banner')

        <!-- Content -->
            @yield('content')            

		<!-- Footer -->
		<div class="w-100" style="height:64px"></div>
		<footer class="blog-footer fixed-bottom bg-light mt-3 p-2">		
			<p class="text-center pt-2">Blog template built for <a href="https://getbootstrap.com/">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a></p>
		</footer>

		<!-- Optional JavaScript -->
    	<!-- jQuery first, then Popper.js, then Bootstrap JS -->		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

	</body>
</html>