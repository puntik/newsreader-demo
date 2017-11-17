@extends('layouts.default')

@section('content')
	<div class="ui two column grid">
		<div class="column">

		</div>
		<div class="column">

			<h4 class="ui dividing header">Sign in</h4>

			<form class="ui form" method="POST" action="{{ route('login') }}">
				{{ csrf_field() }}

				<div class="field">
					<label for="username" class="">E-Mail Address or username</label>
					<input id="username" type="email" class="" name="username" value="{{ old('username') }}" required autofocus>
				</div>

				<div class="field">
					<label for="password" class="">Password</label>
					<input id="password" type="password" class="form-control" name="password" required>
				</div>

				<div class="field">
					<div class="ui toggle checkbox">
						<input type="checkbox" tabindex="0" class="hidden">
						<label>Remember me</label>
					</div>
				</div>

				<button type="submit" class="ui button">
					Login
				</button>
			</form>
		</div>
@endsection
