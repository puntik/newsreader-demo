@extends('layouts.default')

@section('title', __('messages.results'))

@section('sidebar')
	@component('categories', ['categories' => []])
		@lang('Component categories not found or something is wrong')
	@endcomponent
@endsection

@section('content')
	<h1 class="ui header">@lang('messages.foundResultsFor', ['term' => $term])</h1>
	<div class="ui divider"></div>
	<div id="item-grid" class="ui grid two columns">
		@foreach($feeds as $feed)
			@component('feed', ['feed' => $feed])
			@endcomponent
		@endforeach
	</div>
@endsection
