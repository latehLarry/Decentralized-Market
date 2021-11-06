@extends('master.main')

@section('title', 'Account history')

@section('content')

@include('includes.components.menuaccount')
<div class="content-profile">
	<div class="h2 mb-15">Account history</div>
	<div class="inblock">
		All transfers involving your receiving address will be recorded here. You can delete them at any time!
	</div>
	<form action="{{ route('clear.history') }}" method="post" class="inblock mb-10 float-right">
		@csrf
		@method('DELETE')
		<button type="submit">clear</button>		
	</form>
	<table class="zebra table-space">
		<thead>
			<th>action</th>
			<th>amount</th>
			<th>balance</th>
			<th>date</th>
		</thead>
		<tbody>
			@forelse($transitions as $transition)
			<tr>
				<td>{{ $transition->action }}</td>
				<td>{{ $transition->amount }}</td>
				<td>{{ $transition->balance }}</td>
				<td>{{ $transition->created_at }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="4">No transactions registered.</td>
			</tr>
			@endforelse
			<tr>
				<td colspan="4">{{ $transitions->links('includes.components.pagination') }}</td>
			</tr>
		</tbody>
	</table>
</div>

@stop