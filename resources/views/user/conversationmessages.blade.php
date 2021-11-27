@extends('master.main')

@section('title', 'Conversation messages')

@section('content')

<div class="content-profile">
    <div class="h3 mb-20">Messages exchanged with {{ $conversation->otherUser() }}</div>
    <form action="{{ route('post.conversationmessages', ['conversation' => $conversation->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <div class="label">
                <label for="message">message</label>
            </div>
            <textarea id="message" name="message" cols="60" rows="15"></textarea>
            @error('message')
            <div class="error">
                <small class="text-danger">{{ $errors->first('message') }}</small>
            </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="encrypted">Auto encrypt</label>
            <input type="checkbox" id="encrypted" name="encrypted" value="1">
            <div class="info-wrapper" style="margin-left: 10px;">
                <div class="info-folder">
                    <div class="info-icon">?</div>
                    <div class="info-message">The message will automatically be encrypted with the PGP key of the receiving user.</div>
                </div>
            </div> 
        </div>
        @include('includes.forms.captcha')
    </form>
    <div class="messages">
        @foreach($conversationMessages as $conversationMessage)
        <div class="container mt-10" style="width: 485px">
            <textarea cols="57" rows="10" disabled>{{ $conversationMessage->decryptMessage() }}</textarea>
            <div class="mt-20">
                <small>{{ $conversationMessage->user->username }} - {{ $conversationMessage->creationDate() }}</small>
            </div>
        </div>
        @endforeach
    </div>
    {{ $conversationMessages->links('includes.components.pagination') }}
</div>

@stop