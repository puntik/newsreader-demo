@extends('layouts.default')

@section('title', $category->title)

@section('sidebar')
	@component('categories', ['categories' => $categories])
		@lang('Component categories not found or something is wrong')
	@endcomponent
@endsection

@section('content')
	<h1 class="ui header">{{ $category->title }}</h1>
	<div class="ui divider"></div>
	<div id="item-grid" class="ui two columns stackable grid">
		@each('feed', $feeds, 'feed')
	</div>
	<div class="ui divider"></div>
	<div class="ui right">
		{{ $feeds->links('vendor.pagination.semantic-ui') }}
	</div>
@endsection
