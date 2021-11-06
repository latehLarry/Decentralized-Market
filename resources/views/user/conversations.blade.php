@extends('master.main')

@section('title', 'Conversations')

@section('content')

<div class="content-sidebar" style="width: 420px;">
    <div class="footnote">All conversations (active or not) older than 30 days will be deleted!</div>
    <table class="zebra table-space">
        <thead>
            <tr>
                <th>user</th>
                <th>unread</th>
                <th>total messages</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            @foreach($conversations as $conversation)
            <tr>
                <td style="white-space: nowrap">{{ $conversation->otherUser() }}</td>
                <td>{{ $conversation->unreadMessages() }}</td>
                <td>{{ $conversation->totalMessages() }}</td>
                <td><a href="{{ route('conversationmessages', ['conversation' => $conversation->id]) }}">view</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $conversations->links('includes.components.pagination') }}
</div>
<div class="content-conversations">
    @include('includes.flash.error')
    <div class="h2 mb-15">Create a new conversation</div>
    <form action="{{ route('post.conversations') }}" method="post">
        @csrf
        <div class="form-group">
            <div class="label">
                <label for="username">username</label>
            </div>
            <input type="text" id="username" name="username" value="{{ $user }}" style="width: 30%">
            @error('username')
            <div class="error">
                <small class="text-danger">{{ $errors->first('username') }}</small>
            </div> 
            @enderror
        </div>
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
            <label for="encrypted">Encrypt message with receiver's PGP key?</label>
            <input type="radio" id="encrypted" name="encrypted" value="1">yes
            <input type="radio" id="encrypted" name="encrypted" value="0" checked>no
        </div>
        @include('includes.forms.captcha')
    </form>
</div>

@stop