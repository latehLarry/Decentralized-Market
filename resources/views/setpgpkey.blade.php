@extends('master.main')

@section('title', 'Define PGP key')

@section('no-content')

@include('includes.flash.validation')
@include('includes.flash.success')
@include('includes.flash.error')
<div class="master" style="width: 428px">
	<div class="h3">Set PGP key</div>
	<div class="footnote mb-10">To use our services you need to set a pgp key.</div>
	@if(!session()->has('verification_name') and session()->get('verification_name') !== 'confirm_new_pgp_key')
		<form action="{{ route('post.setpgpkey') }}" method="post">
			@csrf
			<div class="label">
				<label for="pgp_key">PGP key</label>
			</div>
			<textarea id="pgp_key" name="pgp_key" cols="50" rows="12"></textarea>
			<div class="mt-10">
				<button type="submit">next step</button>
			</div>
		</form>
	@else
		<form action="{{ route('put.setpgpkey') }}" method="post">
			@csrf
			@method('PUT')
			<div class="label">
				<label>encrypted message</label>
			</div>
			<textarea cols="50" rows="12" style="background-color: #fff" disabled>{{ session()->get('encrypted_message') }}</textarea>
			<div class="footnote">Decrypt the above message with the PGP key entered and copy and paste the verification code into the field below.</div>
			<div class="label mt-10">
				<label for="verification_code">verification code</label>
			</div>
			<input type="text" id="verification_code" name="verification_code">
			<div class="mt-10">
				<a href="{{ route('cancelsetpgpkey') }}">cancel</a>
				<button type="submit">confirm</button>
			</div>
		</form>
	@endif
</div>

@stop