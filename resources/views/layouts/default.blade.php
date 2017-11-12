<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta charset="UTF-8">
		<title>@yield('title') - newsreader.cz</title>
		<link rel="stylesheet" href="/semantic/semantic.min.css"/>
	</head>
	<body>
		<div class="ui sidebar wide vertical menu">
			@section('sidebar')
				This is the master sidebar.
			@show
		</div>
		<div class="pusher">
			<a href="#" id="show-menu-button">Show menu</a>
			<a href="#" id="show-3-columns">3 columns</a>
			<a href="#" id="show-2-columns">2 columns</a>
			<a href="#" id="show-description">Show description</a>
			<div class="ui container">
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

			$('#show-3-columns').click(function() {
				$('#item-grid').removeClass('two columns').addClass('three columns');
			});

			$('#show-2-columns').click(function() {
				$('#item-grid').removeClass('three columns').addClass('two columns');
			});

			$('#show-description').click(function() {
				$('.description').toggle();
			});

		</script>

	</body>
</html>
