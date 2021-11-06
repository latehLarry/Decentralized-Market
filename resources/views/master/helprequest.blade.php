@extends('master.main')

@section('title', 'Help request: '.$helpRequest->decryptTitle())

@section('content')

<div class="content-browsing">
	@include('includes.flash.validation')
	@include('includes.flash.success')
	@include('includes.flash.error')
	<div class="h2 mb-20">Help request: {{ $helpRequest->decryptTitle() }} ({{ $helpRequest->status() }})</div>
	@staff
	<form action="{{ route('post.staff.closehelprequest', ['helpRequest' => $helpRequest->id]) }}" method="post" class="mb-20">
		@csrf
		<button type="submit">close this help request</button>
	</form>
	@endstaff
	<div class="h3 mb-10">Create new message</div>
	<form action="{{ route('post.helprequest', ['helpRequest' => $helpRequest->id]) }}" method="post">
		@csrf
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
    <div class="messages">
        @foreach($messages as $message)
        <div class="container mt-10" style="width: 485px">
            {{ $message->decryptMessage() }}
            <div class="mt-20">
                <small>{{ $message->user->username }} - {{ $message->creationDate() }}</small>
            </div>
        </div>
        @endforeach
        <div class="">
        	{{ $messages->links('includes.components.pagination') }}
        </div>
    </div>
</div>

@stop