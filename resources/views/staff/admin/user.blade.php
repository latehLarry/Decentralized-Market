@extends('master.main')

@section('title', 'Admin edit user')

@section('content')

@include('includes.components.menustaff')
<div class="content-profile">
	@include('includes.flash.validation')
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h3">Edit user {{ $user->username }}</div>
	<div class="container mt-10">
		<div class="h3 mb-10">Basic information</div>
		<form action="{{ route('put.admin.edituser', ['user' => $user->id]) }}" method="post">
			@csrf
			@method('PUT')
			<div class="inblock">
				<label for="username">username</label>
				<input type="text" id="username" name="username" value="{{ $user->username }}">
			</div>
			<div class="inblock">
				<label for="reference_code">reference code</label>
				<input type="text" id="reference_code" name="reference_code" value="{{ $user->reference_code }}">
			</div>
			<div class="inblock">
				<label for="finalizearly">finalize early</label>
				<input type="radio" id="finalizearly" name="finalizearly" value="1" @if($user->finalizeEarly()) checked @endif>yes
				<input type="radio" id="finalizearly" name="finalizearly" value="0" @if(!$user->finalizeEarly()) checked @endif>no
			</div>
			<br>
			<div class="float-right">
				<strong>last login: {{ $user->lastLogin() }}</strong>
			</div>
			<div style="background-color: #ccc; height: 1px; width: 75%; margin-top: 10px"></div>
			<div class="h3 mt-20">PGP key</div>
			<textarea style="background-color: #fff" rows="15" cols="40" disabled>{{ $user->pgp_key }}</textarea>
			<div class="h3 mt-20 mb-10">Role</div>
			<input type="checkbox" id="seller" name="role[]" value="seller" @if($user->isSeller()) checked @endif><label for="seller">seller</label>
			<input type="checkbox" id="moderator" name="role[]" value="moderator" @if($user->isModerator()) checked @endif><label for="moderator">moderator</label>
			<input type="checkbox" id="admin" name="role[]" value="admin" @if($user->isAdmin()) checked @endif><label for="admin">admin</label>
			<div class="float-right">
				<button type="submit">edit {{ $user->username }}</button>
			</div>
		</form>
	</div>
</div>

@stop