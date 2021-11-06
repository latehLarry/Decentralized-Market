@extends('master.main')

@section('title', 'Staff mass message')

@section('content')

@include('includes.components.menustaff')
<div class="content-profile">
	@include('includes.flash.validation')
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h3 mb-10">Send mass message</div>
	<form action="{{ route('post.staff.massmessage') }}" method="post">
		@csrf
		<div class="form-group">
			<div class="label">
				<label for="message">message</label>
			</div>
			<textarea id="message" name="message" cols="60" rows="15"></textarea>
		</div>
		<div class="row">
			<div class="inblock">
				<input type="checkbox" value="buyers" name="group[]">buyers
			</div>
			<div class="inblock">
				<input type="checkbox" value="sellers" name="group[]">sellers
			</div>
			<div class="inblock">
				<input type="checkbox" value="staff" name="group[]">staff
			</div>
		</div>
		<div class="mt-10">
			<button type="submit">submit</button>
		</div>
	</form>
</div>

@stop