@extends('master.main')

@section('title', 'Register')

@section('no-content')

@include('includes.flash.error')
<div class="master" style="width: 300px">
	<form action="{{ route('post.register') }}" method="post">
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
		</div>
		<div class="form-group">
			<input type="password" id="password_confirmation" name="password_confirmation">
			<label for="password_confirmation">confirm password</label>
			@error('password')
			<div class="error">
				<small class="text-danger">{{ $errors->first('password') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group">
			<input type="password" id="pin" name="pin" maxlength="6">
			<label for="pin">PIN (6 numeric digits)</label>
		</div>
		<div class="form-group">
			<input type="password" id="pin_confirmation" name="pin_confirmation" maxlength="6">
			<label for="pin_confirmation">confirm PIN</label>
			@error('pin')
			<div class="error">
				<small class="text-danger">{{ $errors->first('pin') }}</small>
			</div>
			@enderror
		</div>
		<div class="form-group">
			<input type="text" id="reference_code" name="reference_code" value="{{ $reference_code }}">
			<label for="reference_code">reference (optional)</label>
			@error('reference_code')
			<div class="error">
				<small class="text-danger">{{ $errors->first('reference_code') }}</small>
			</div>
			@enderror
		</div>
		@include('includes.forms.captcha')
	</form>
	<div class="mt-10">
		<a href="{{ route('login') }}">&rarr; click here to login</a>
	</div>
</div>

@stop