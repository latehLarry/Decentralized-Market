@extends('master.main')

@section('title', 'Staff reports')

@section('content')

@include('includes.components.menustaff')
<div class="content-profile">
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h3">All reports ({{ $reports->count() }})</div>
	<table class="zebra mt-10" style="width: 100%; text-align: center">
		<thead>
			<tr>
				<th>product</th>
				<th>author</th>
				<th>cause</th>
				<th>message</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody>
			@forelse($reports as $report)
			<tr>
				<td><a href="{{ route('product', ['product' => $report->product->id]) }}">{{ $report->product->name }}</a></td>
				<td>{{ $report->user->username }}</td>
				<td>{{ $report->cause }}</td>
				<td>
					<textarea disabled style="background-color: #fff">{{ $report->decryptMessage() }}</textarea>
				</td>
				<td>
					<form action="{{ route('delete.staff.report', ['report' => $report->id]) }}" method="post">
						@csrf
						@method('DELETE')
						<button type="submit" class="text-danger">delete</button>
					</form>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="5">The market does not have any reports yet!</td>
			</tr>
			@endforelse
			<tr>
				<td colspan="5">{{ $reports->links('includes.components.pagination') }}</td>
			</tr>
		</tbody>
	</table>
</div>

@stop