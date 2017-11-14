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
		<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
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

			// Enable pusher logging - don't include this in production
			Pusher.logToConsole = true;

			var pusher = new Pusher('527501b81bcc2f3f7b23', {
				cluster: 'eu',
				encrypted: true
			});

			var channel = pusher.subscribe('my-channel');
			channel.bind('my-event', function(data) {
				alert(data.message);
			});
		</script>

	</body>
</html>
