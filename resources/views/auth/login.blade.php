@extends('layouts.default')

@section('content')
	<div class="ui two column grid">
		<div class="column">

		</div>
		<div class="column">

			<h4 class="ui dividing header">@lang('messages.signIn')</h4>

			<form class="ui form @if($errors->any())error @endif" method="POST" action="{{ route('login') }}">
				@if($errors->any())
					<div class="ui error message">
						<div class="header">@lang('auth.form_title')</div>
						<ul class="list">
							<li>{{ $errors->first() }}</li>
						</ul>
					</div>
				@endif

				{{ csrf_field() }}
				<div class="field">
					<label for="username" class="">@lang('messages.usernameOrEmail')</label>
					<input id="username" type="email" class="" name="username" value="{{ old('username') }}" required autofocus>
				</div>

				<div class="field">
					<label for="password" class="">@lang('messages.password')</label>
					<input id="password" type="password" class="form-control" name="password" required>
				</div>

				<div class="field">
					<div class="ui toggle checkbox">
						<input type="checkbox" tabindex="0" class="hidden">
						<label>@lang('messages.rememberMe')</label>
					</div>
				</div>

				<button type="submit" class="ui button">
					@lang('messages.signIn')
				</button>
			</form>
		</div>
	</div>
@endsection
