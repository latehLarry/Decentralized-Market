@extends('master.main')

@section('title', 'Account statistics')

@section('content')

@include('includes.components.menuaccount')
<div class="content-profile">
	<div class="h2 mb-15">Account statistics</div>
	@include('includes.components.buyerstats')
	<div class="container mt-10">
		<div class="footnote">These statistics are made available to your vendors when you purchase an item so they can better judge whether they want to do business with you or not. The more transactions you make that go smoothly, the more likely your next order will be accepted.</div>
	</div>
</div>

@stop