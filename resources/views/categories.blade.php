<div>
	@foreach($categories as $category)
		<a class="item" href="{{ url('/hello/'. $category->id) }}">
			{{ $category->title }}
			<div class="ui grey label">{{ $category->last_week }} / {{ $category->today }}</div>
		</a>
	@endforeach
</div>
