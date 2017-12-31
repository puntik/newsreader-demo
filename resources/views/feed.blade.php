<div class="ui column">
	<div>
		<a href="{{ $feed->link }}" target="_blank" class="ui small header" style="text-decoration: underline;">{{ $feed->title }}</a>
		<div>{{ $feed->published_at }}</div>
		<p class="description">{{ str_limit($feed->description, 350, ' ..') }}</p>
		<div>
			<div class="ui basic label">
				<i class="cz flag"></i> {{ $feed->source->title }}
			</div>
			@if($feed->ageInHours() === 1)
				<div class="ui label">@lang('messages.oneHourAgo')</div>
			@endif
			@if($feed->ageInHours() < (new DateTime())->format('H'))
				<div class="ui label">@lang('messages.today')</div>
			@endif
		</div>
	</div>
</div>

