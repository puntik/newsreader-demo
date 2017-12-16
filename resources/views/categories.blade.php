<div>
	<a class="item" href="{{ url('/') }}">Home</a>
	@foreach($categories as $category)
		<a class="item" href="{{ route('category', ['id' => $category->id, 'name' => str_slug($category->title)]) }}">
			{{ $category->title }}
			<div class="ui grey label">{{ $category->last_week }} / {{ $category->today }}</div>
		</a>
	@endforeach
</div>
