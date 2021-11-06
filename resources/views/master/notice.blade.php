@extends('master.main')

@section('title', $notice->title)

@section('content')

<div class="content-browsing">
   <div class="container about">
   		<div class="h3">{{ $notice->title }}</div>
		{!! Illuminate\Support\Str::markdown(strip_tags($notice->notice)) !!}
	   <div class="h3">
	   	Your faithful servant,<br> 
	   	{{ $notice->user->username }}
	   </div>
   </div>
</div>

@stop