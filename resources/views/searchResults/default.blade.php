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
		@each('feed', $feeds, 'feed')
	</div>
	<div class="ui divider"></div>
	<div class="ui right">
		{{ $feeds->links('vendor.pagination.semantic-ui') }}
	</div>
@endsection
