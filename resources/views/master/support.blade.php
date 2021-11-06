@extends('master.main')

@section('title', 'Support')

@section('content')

<div class="content-browsing">
	@include('includes.flash.validation')
	@include('includes.flash.error')
	<div class="content-sidebar" style="width:320px;">
		<div class="footnote">Help requests marked as closed are automatically deleted in 30 days!</div>
	    <table class="zebra" style="width: 100%">
	        <thead>
	            <tr>
	                <th>title</th>
	                <th>status</th>
	                <th>#</th>
	            </tr>
	        </thead>
	        <tbody>
	            @forelse($helpRequests as $helpRequest)
	            <tr>
	                <td>{{ $helpRequest->decryptTitle() }}</td>
	                <td><strong>{{ $helpRequest->status() }}</strong></td>
	                <td><a href="{{ route('helprequest', ['helpRequest' => $helpRequest->id]) }}">view</a></td>
	            </tr>
	            @empty
	            <tr>
	            	<td colspan="3">You haven't asked for any help yet!</td>
	            </tr>
	            @endforelse
	            <tr>
	            	<td colspan="3">{{ $helpRequests->links('includes.components.pagination') }}</td>
	            </tr>
	        </tbody>
	    </table>
	</div>
	<div class="h2 mb-20">Support</div>
	<div class="h3 mb-10">Create help request</div>
	<form action="{{ route('post.createhelprequest') }}" method="post">
		@csrf
		<div class="form-group inblock">
			<div class="label">
				<label for="title">title</label>
			</div>
			<input type="text" id="title" name="title">
		</div>
		<div class="info-wrapper inblock">
			<div class="info-folder">
				<div class="info-icon">?</div>
				<div class="info-message">If you have any questions, please contact the team! You can only have one help request open at a time.</div>
			</div>
		</div>
		<div class="form-group">
			<div class="label">
				<label for="message">message</label>
			</div>
			<textarea id="message" name="message" rows="20" cols="50"></textarea>
		</div>
		<div class="form-group">
			<button type="submit">submit</button>
		</div>
	</form>
</div>

@stop