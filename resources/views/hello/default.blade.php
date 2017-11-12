@extends('layouts.default')

@section('title', $category->title)

@section('sidebar')
	@component('categories', ['categories' => $categories])
		Component categories not found or something is wrong
	@endcomponent
@endsection

@section('content')
	<h1 class="ui header">{{ $category->title }}</h1>
	<div class="ui divider"></div>
	<div id="item-grid" class="ui grid two columns">
		@foreach($feeds as $feed)
			<div class="ui column">
				<div>
					<a href="{{ $feed->link }}" target="_blank" class="ui small header" style="text-decoration: underline;">{{ $feed->title }}</a>
					<div>{{ $feed->published_at }}</div>
					<p class="description">{{ str_limit($feed->description, 300, ' ..') }}</p>
					<div>
						<div class="ui basic label"><i class="{{ $flags[$feed->language] }} flag"></i></div>
						<div class="ui label"><i class="globe icon"></i> {{ $feed->source }}</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>

	<div class="ui center">
		Records: {{ $feeds->total() }}<br/>
		{{ $feeds->links('vendor.pagination.semantic-ui') }}
	</div>
@endsection
