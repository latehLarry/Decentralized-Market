@extends('master.main')

@section('title', 'Login')

@section('no-content')

@include('includes.flash.success')
@include('includes.flash.error')
<div class="master">
	<form action="{{ route('post.login') }}" method="post">
		@csrf
		<div class="form-group">
			<input type="text" id="username" name="username">
			<label for="username">username</label>
			@error('username')
			<div class="error">
				<small class="text-danger">{{ $errors->first('username') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group">
			<input type="password" id="password" name="password">
			<label for="password">password</label>
			<a href="{{ route('resetpassword') }}">forgot password?</a>
			@error('password')
			<div class="error">
				<small class="text-danger">{{ $errors->first('password') }}</small>
			</div>
			@enderror
		</div>
		@include('includes.forms.captcha')
	</form>
	<div class="mt-10">
		<a href="{{ route('register') }}">&rarr; click here to join</a>
	</div>
</div>

@stop