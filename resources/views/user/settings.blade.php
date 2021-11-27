@extends('master.main')

@section('title', 'Settings')

@section('content')

@include('includes.components.menuaccount')
<div class="content-profile">
	@include('includes.flash.validation')
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h2 mb-15">Account Settings</div>
	<div class="container mb-20">
		<div class="info-wrapper float-right">
			<div class="info-folder">
				<div class="info-icon">?</div>
				<div class="info-message">Choose an image that is less than 30kb and is in png or jpg format.</div>
			</div>
		</div>
		<div class="h3 mb-10">Change avatar</div>
		<form action="{{ route('put.changeavatar') }}" method="post" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			<img src="{{ auth()->user()->avatar }}" width="96px" height="96px">
			<div class="form-group container" style="width: 55%">
				<label for="avatar">avatar</label>
				<input type="file" id="avatar" name="avatar">
				<button type="submit">change avatar</button>
			</div>
		</form>
	</div>
	<div class="container mb-20">
		<div class="info-wrapper float-right">
			<div class="info-folder">
				<div class="info-icon">?</div>
				<div class="info-message">To change your password, simply enter your current password in the corresponding field and then enter a new password.</div>
			</div>
		</div>
		<div class="h3 mb-10">Change password</div>
		<form action="{{ route('put.changepassword') }}" method="post">
			@csrf
			@method('PUT')
			<div class="label">
				<label for="current_password">current password</label>
			</div>
			<input type="password" id="current_password" name="current_password">
			<div class="label">
				<label for="new_password">new password</label>
			</div>
			<input type="password" id="new_password" name="new_password">
			<div class="label">
				<label for="new_password_confirmation">confirm new password</label>
			</div>
			<input type="password" id="new_password_confirmation" name="new_password_confirmation">
			<div class="mt-10">
				<button type="submit">change password</button>
			</div>
		</form>
	</div>
	<div class="container mb-20">
		<div class="info-wrapper float-right">
			<div class="info-folder">
				<div class="info-icon">?</div>
				<div class="info-message">To change your PIN, simply enter your current PIN in the corresponding field and then enter a new PIN.</div>
			</div>
		</div>
		<div class="h3 mb-10">Change PIN</div>
		<form action="{{ route('put.changepin') }}" method="post">
			@csrf
			@method('PUT')
			<div class="label">
				<label for="current_pin">current PIN</label>
			</div>
			<input type="password" id="current_pin" name="current_pin" maxlength="6">
			<div class="label">
				<label for="new_pin">new PIN</label>
			</div>
			<input type="password" id="new_pin" name="new_pin" maxlength="6">
			<div class="label">
				<label for="new_pin_confirmation">confirm new PIN</label>
			</div>
			<input type="password" id="new_pin_confirmation" name="new_pin_confirmation" maxlength="6">
			<div class="mt-10">
				<button type="submit">change PIN</button>
			</div>
		</form>
	</div>
	<div class="container mb-20">
		<div class="info-wrapper float-right">
			<div class="info-folder">
				<div class="info-icon">?</div>
				<div class="info-message">All market prices will be displayed according to the chosen currency.</div>
			</div>
		</div>
		<div class="h3 mb-10">Local currency</div>
		<form action="{{ route('post.changecurrency') }}" method="post">
			@csrf
			<select id="currency" name="currency" class="dropdown-wrapper">
				@foreach(config('currencies') as $currency)
				<option value="{{ $currency }}" @if($currency == auth()->user()->currency) selected @endif>{{ $currency }}</option>
				@endforeach
			</select>
			<button type="submit">change</button>
		</form>
	</div>
	<div class="container mb-20">
		<div class="h3 mb-10 inblock">Backup wallet</div>
		<div class="info-wrapper float-right">
			<div class="info-folder">
				<div class="info-icon">?</div>
				<div class="info-message">If for some reason this site has to be shut down unexpectedly, the available moneros in your account will be sent here.</div>
			</div>
		</div>
		<form action="{{ route('put.changebackupwallet') }}" method="post">
			@csrf
			@method('PUT')
			<div class="form-group">
	        	<label for="monero_wallet_address">monero wallet address</label>
	        	<input type="text" id="monero_wallet_address" name="monero_wallet_address" style="font-family: Courier; font-size: 90%; background-color: #fff; color: #000; font-weight: bold; width: 280px" value="{{ auth()->user()->backup_monero_wallet }}" maxlenght="35">
	        	<button type="submit">change</button>
	        </div>
	    </form>
	</div>
	<div class="container" id="pgpkey">
		<div class="h3 mb-10 inblock">Change PGP key</div>
		<div class="footnote inblock" style="margin-left: 10px"><a href="{{ route('pgpkey') }}" target="__blank">&rarr; see your current PGP key</a></div>
		<div class="info-wrapper float-right">
			<div class="info-folder">
				<div class="info-icon">?</div>
				<div class="info-message">A message will be encrypted with the PGP key entered. You will have to decrypt it and paste the verification code to confirm the PGP key change.</div>
			</div>
		</div>
		@if(!session()->has('verification_name') and session()->get('verification_name') !== 'confirm_new_pgp_key')
		<form action="{{ route('post.changepgpkey') }}" method="post">
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
		<form action="{{ route('put.changepgpkey') }}" method="post">
			@csrf
			@method('PUT')
			<div class="label">
				<label>encrypted message</label>
			</div>
			<textarea cols="50" rows="12" style="background-color: #fff" disabled>{{ session()->get('encrypted_message') }}</textarea>
			<div class="footnote">Decrypt the above message with the PGP key entered and copy and paste<br>the verification code into the field below.</div>
			<div class="label mt-10">
				<label for="verification_code">verification code</label>
			</div>
			<input type="text" id="verification_code" name="verification_code">
			<div class="mt-10">
				<a href="{{ route('cancelpgpkeychange') }}">cancel</a>
				<button type="submit">confirm and change</button>
			</div>
		</form>
		@endif
	</div>
</div>

@stop