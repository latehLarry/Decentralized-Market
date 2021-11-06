@extends('master.main')

@section('title', 'Staff edit notice')

@section('content')

@include('includes.components.menustaff')
<div class="content-profile">
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h3 mb-20">Notice: {{ $notice->title }}</div>
	<div style="width: 61%">
		<form action="{{ route('put.staff.editnotice', ['notice' => $notice->id]) }}" method="post" class="mb-40">
			@csrf
			@method('PUT')
			<div class="form-group">
				<div class="label">
					<label for="title">title</label>
				</div>
				<input type="text" id="title" name="title" style="width: 50%" maxlength="50" value="{{ $notice->title }}">
				<div class="error">
					@error('title')
					<small class="text-danger">{{ $errors->first('title') }}</small>
					@enderror
				</div>
			</div>
			<div class="form-group">
				<div class="label">
					<label for="notice">notice</label>
				</div>
				<textarea id="notice" name="notice" cols="55" rows="20">{{ $notice->notice }}</textarea>
				<div class="notice">
					@error('notice')
					<small class="text-danger">{{ $errors->first('notice') }}</small>
					@enderror
				</div>
			</div>
			<button><a href="{{ route('staff.notices') }}" class="button">back</a></button>
			<button class="submit float-right inblock">submit</button>
		</form>
	</div>
</div>

@stop