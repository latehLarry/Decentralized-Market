@extends('master.main')

@section('title', 'Staff notices')

@section('content')

@include('includes.components.menustaff')
<div class="content-profile">
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h3">Add new notice</div>
	<div style="width: 61%; margin-bottom: 60px" class="mt-10">
		<form action="{{ route('post.staff.addnotice') }}" method="post" class="mb-40">
			@csrf
			<div class="form-group">
				<div class="label">
					<label for="title">title</label>
				</div>
				<input type="text" id="title" name="title" style="width: 50%" maxlength="50">
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
				<textarea id="notice" name="notice" cols="55" rows="20"></textarea>
				<div class="notice">
					@error('notice')
					<small class="text-danger">{{ $errors->first('notice') }}</small>
					@enderror
				</div>
			</div>
			<button class="submit float-right">submit</button>
		</form>
	</div>
	<div class="h3">All notices ({{ $notices->count() }})</div>
	<table class="zebra table-space">
		<thead>
			<tr>
				<th>title</th>
				<th>author</th>
				<th>created at</th>
				<th>updated at</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody>
			@forelse($notices as $notice)
			<tr>
				<td><a href="{{ route('notice', ['notice' => $notice->id]) }}">{{ $notice->title }}</a></td>
				<td>{{ $notice->user->username }}</td>
				<td>{{ $notice->createdAt() }}</td>
				<td>{{ $notice->updatedAt() }}</td>
				<td>
					<button><a href="{{ route('staff.notice', ['notice' => $notice->id]) }}" class="button">edit</a></button>
					<form action="{{ route('delete.staff.notice', ['notice' => $notice->id]) }}" class="inblock" method="post">
						@csrf
						@method('DELETE')
						<button class="text-danger">delete</button>
					</form>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="5">The market does not have any warnings yet!</td>
			</tr>
			@endforelse
			<tr>
				<td colspan="5">{{ $notices->links('includes.components.pagination') }}</td>
			</tr>
		</tbody>
	</table>
</div>

@stop