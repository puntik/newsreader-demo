<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="/favicon.png">
		<meta charset="UTF-8">
		<title>@yield('title') - newsreader.cz</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link href="{{ asset('semantic/semantic.min.css') }}" rel="stylesheet">
	</head>
	<body>
		<div class="ui sidebar wide vertical menu">
			@section('sidebar')
				This is the master sidebar.
			@show
		</div>
		<div class="pusher">
			<div class="ui container">
				<div class="ui link menu">
					<div class="item" id="show-menu-button">
						<i class="content icon"></i>
					</div>
					<div class="item">
						<a href="/"><i class="home icon"></i></a>
					</div>
					<div class="right menu">
						<div class="item">
							<div class="ui transparent icon input">
								<form method="get" action="{{ route('searchResult') }}">
									<input type="text" name="q" placeholder="Search...">
									{{ csrf_field() }}
									<i class="search link icon"></i>
								</form>
							</div>
						</div>
						<div class="item">
							@guest
								<a href="/login">@lang('messages.signIn')</a>
							@else
								<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									@lang('messages.signOut')
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							@endguest
						</div>
					</div>
				</div>
				@yield('content')
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="/semantic/semantic.min.js"></script>
		<script type="text/javascript">
			$('#show-menu-button').click(function() {
					$('.ui.sidebar')
					.sidebar('setting', 'transition', 'overlay')
					.sidebar('toggle');
				}
			);
			$('.ui.checkbox').checkbox();
		</script>
	@if(\Illuminate\Support\Facades\App::environment('production'))
		<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-8529556-11"></script>
			<script>
				window.dataLayer = window.dataLayer || [];

				function gtag() {
					dataLayer.push(arguments);
				}

				gtag('js', new Date());

				gtag('config', 'UA-8529556-11');
			</script>
		@endif
	</body>
</html>
