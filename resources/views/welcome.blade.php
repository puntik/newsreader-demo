@extends('layouts.default')

@section('sidebar')
	@component('categories', ['categories' => $categories])
		@lang('Component categories not found or something is wrong')
	@endcomponent
@endsection

@section('content')
	<div class="ui cards">
		@foreach($categories as $category)
			<div class="card">
				<div class="content">
					<div class="header">
						<a href="{{ route('category', ['id'=>$category->id, 'name'=>str_slug($category->title)]) }}">
							<i class="external square icon"></i>
						</a>
						{{ $category->title }}</div>
					<div class="meta">
						{{ $category->today }} dnes /
						{{ $category->last_week }} za týden
					</div>{{--
					<div class="description">
						<div class="ui  list">
							@for($i = 0; $i < 3; $i++)
								<div class="item">
									<i class="hashtag middle aligned icon"></i>
									<div class="content">
										<a class="header">Semantic-Org/Semantic-UI</a>
										<div class="description">Updated 10 mins ago</div>
									</div>
								</div>
							@endfor
						</div>
					</div>
					--}}
				</div>
			</div>
		@endforeach
	</div>
@endsection
