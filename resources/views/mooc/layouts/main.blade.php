<!DOCTYPE HTML>

<html>
	<head>
		<title>{{ __('dictionary.Website Name') }}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
		<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		
		@yield('head-extension')   
		<script>
			$(document).ready(function() {
                $("#searchClass").find("input").mouseover(function() {
                    $("#searchClass").css("width","100%");
                });
                //$("#searchClass").find("input").mouseout(function() {
                //    $("#searchClass").css("width","15em");
                //});

                $("#findClass").click(function() {
                    var input = $("#searchClass").find("input").val();					
                    window.location.assign("{{ route('class') }}?search=" + input);
                });

				$.ajax({
					type: "get",
					datatype: "json",
					url: "/api/class/getOptions",
					data: {"_token": "{{ csrf_token() }}"},
					success: function(response){
						var options = jQuery.parseJSON(response.data);
						for (var index in options) {												
							var option = '<option value="' + options[index] + '">';
							$("#classList").append(option);
						}
					}
				});

			 	@yield('script-extension')   
			});
    	</script>
		<style>
			.icon {
				max-width: 200px;
				max-height: 56px;
				margin-right: 20px;
			}
		</style>
	</head>
	<body>
		<!-- Nav -->
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<img class="icon" src="{{ asset('images/icon.png') }}">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
        	<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link text-nowrap" href="{{ route('index') }}">{{ __('dictionary.HomePage') }} <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-nowrap" href="{{ route('member') }}">{{ __('dictionary.Account') }}</a>
					</li>
					<li class="nav-item mr-5">
						<div class="btn-group">
							<button type="button" class="btn btn-dark dropdown-toggle text-nowrap" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								{{ __('dictionary.Select By School') }}
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="{{ route('class') }}?school=ntu">{{ __('dictionary.NTU') }}</a>
								<a class="dropdown-item" href="{{ route('class') }}?school=nthu">{{ __('dictionary.NTHU') }}</a>
								<a class="dropdown-item" href="{{ route('class') }}?school=nctu">{{ __('dictionary.NCTU') }}</a>
							</div>						
						</div>
					</li>					
				</ul>

		<!-- 課程搜尋input框 -->
				<div class="input-group mr-2" id="searchClass" style="width:15em;">
					<input type="text" id="searchClass" class="form-control" list="classList" placeholder="{{ __('dictionary.Search Class') }}">
					<datalist id="classList"></datalist>
					
					<div class="input-group-append">
						<button id="findClass" class="btn btn-outline-info" type="button">{{ __('dictionary.Submit') }}</button>
					</div>
				</div>

		<!-- 登入等按鈕 -->
				<ul class="navbar-nav mr-2em">
					@if (Auth::check())
						<li class="nav-item">
							<a class="nav-link text-nowrap" href="{{ route('logout') }}">{{ __('auth.Logout') }}</a>
						</li>
					@else
						<li class="nav-item">
							<a class="nav-link text-nowrap" href="{{ route('login') }}">{{ __('auth.Login') }}</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-nowrap" href="{{ route('register') }}">{{ __('auth.Register') }}</a>
						</li>
					@endif
				</ul>			
        	</div>
      	</nav>
		<div style="height:56px;"></div>

		<!-- PHP Error message -->
		@if (isset($errors))
			@foreach (($errors->all()) as $message)
				<div class="container mt-5">
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<strong>{{ $message }}</strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			@endforeach
		@endif

		<!-- JS Error message, default hide-->
			<div class="container" >
				<div class="alert alert-warning alert-dismissible mt-5 fade show d-none" role="alert">
					<strong></strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>

		<!-- Banner -->
			@yield('banner')

        <!-- Content -->
            @yield('content')            

		<!-- Footer -->
		<div class="w-100" style="height:64px"></div>
		<footer class="blog-footer fixed-bottom bg-light mt-3 p-2">		
			<p class="text-center pt-2">Made by CL with Laravel & Bootstrap</p>
		</footer>

		<!-- Optional JavaScript -->
    	<!-- jQuery first, then Popper.js, then Bootstrap JS -->		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

	</body>
</html>