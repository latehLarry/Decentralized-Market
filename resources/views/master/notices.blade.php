@extends('master.main')

@section('title', 'Notice diary')

@section('content')

<div class="content-browsing">
	<div class="h2 mb-20">Notice diary ({{ $notices->count() }})</div>
	<table class="zebra table-space">
		<thead>
			<tr>
				<th>title</th>
				<th>author</th>
				<th>created at</th>
				<th>updated at</th>
			</tr>
		</thead>
		<tbody>
			@forelse($notices as $notice)
			<tr>
				<td><a href="{{ route('notice', ['notice' => $notice->id]) }}">{{ $notice->title }}</td>
				<td>{{ $notice->user->username }}</td>
				<td>{{ $notice->createdAt() }}</td>
				<td>{{ $notice->updatedAt() }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="4">There are still no notices around here!</td>
			</tr>
			@endforelse
			<tr>
				<td colspan="4">{{ $notices->links('includes.components.pagination') }}</td>
			</tr>
		</tbody>
	</table>
</div>

@stop