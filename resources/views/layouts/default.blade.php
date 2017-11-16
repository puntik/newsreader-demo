<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="/favicon.png">
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
			<div class="ui container">
				<div class="ui menu">
					<div class="item" id="show-menu-button">
						<i class="content icon"></i>
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
		</script>
	</body>
</html>
