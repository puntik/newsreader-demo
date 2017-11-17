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
						<div class="ui basic label">
							<i class="{{ $flags[$feed->language] }} flag"></i> {{ $feed->source }}
						</div>
						@if($feed->age_hours === 0)<div class="ui label">před hodinou</div>@endif
						@if($feed->age_hours < (new DateTime())->format('H'))<div class="ui label">dnes</div>@endif
					</div>
				</div>
			</div>
		@endforeach
	</div>
	<div class="ui divider"></div>
	<div class="ui right">
		{{ $feeds->links('vendor.pagination.semantic-ui') }}
	</div>
@endsection
