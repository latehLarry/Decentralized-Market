@extends('master.main')

@section('title', 'Report product')

@section('no-content')

@include('includes.flash.validation')
@include('includes.flash.success')
@include('includes.flash.error')
<div style="text-align: center; margin: 0 auto; margin-top: 50px">
	<div class="flashdata mt-20">Thank you for submitting a report!<br>You may add additional details below or simply close this tab.</div>
</div>
<div class="master" style="width: 300px">
	<div class="h3 mb-20">Report product</div>
	<form action="{{ route('post.report', ['product' => $product->id]) }}" method="post">
		@csrf
		@foreach(config('general.reporting_causes') as $index => $cause)
			<div class="option">
				<input type="radio" id="cause" name="cause" value="{{ $index }}"><label>{{ $cause }}</label>
				@if($index == 'other')
					<input type="text" id="other_cause" name="other_cause" placeholder="optional">
				@endif
			</div>
		@endforeach
		<div class="mt-20">
			<label for="message">optional message</label>
			<textarea id="message" name="message" rows="10" cols="34"></textarea>
		</div>
		@include('includes.forms.captcha')
	</form>
</div>

@stop