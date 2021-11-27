@extends('master.main')

@section('title', 'Reset password')

@section('no-content')

@include('includes.flash.success')
@include('includes.flash.error')
@include('includes.components.logo')
<div class="master" style="width: 306px">
	<div class="h3">Reset password</div>
	<div class="footnote mb-10">You have requested to change the password.</div>
	@if(!session()->has('verification_name') or session()->get('verification_name') !== 'reset_password')
	<form action="{{ route('put.resetpassword') }}" method="post">
		@csrf
		<div class="form-group">
			<input type="text" id="username" name="username">
			<label for="username">username</label>
		</div>
		@include('includes.forms.captcha')
	</form>
	@else
	<form action="{{ route('post.resetpassword') }}" method="post">
		@csrf
		@method('put')
		<textarea cols="35" rows="10" class="mb-15" disabled>{{ session()->get('encrypted_message') }}</textarea>
		<div class="form-group">
			<input type="password" id="new_password" name="new_password">
			<label for="new_password">new password</label>
		</div>
		<div class="form-group">
			<input type="password" id="new_password_confirmation" name="new_password_confirmation">
			<label for="new_password_confirmation">confirm new password</label>
			@error('new_password')
			<div class="error">
				<small class="text-danger">{{ $errors->first('new_password') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group">
			<input type="text" id="verification_code" name="verification_code">
			<label for="verification_code">verification code</label>
		</div>
		@include('includes.forms.captcha')
	</form>
	@endif
	<div class="mt-10">
		<a href="{{ route('login') }}">&rarr; click here to login</a>
	</div>
</div>

@stop