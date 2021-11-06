@extends('master.main')

@section('title', 'Affiliate system')

@section('content')

@include('includes.components.menuaccount')
<div class="content-profile">
	<div class="h2 mb-15">Affiliate system</div>
    <div class="flashdata flashdata-success mb-10">You were referenced by: {{ !is_null($reference) ? $reference->username : 'no one'}}</div>
    <div class="container">
        <div class="h3 mb-10">Your referral link</div>
        <input type="text" value="{{ route('register', ['reference' => auth()->user()->reference_code]) }}" disabled style="width: 48%; background-color: #fff">
        <div class="footnote">Share your referral link with your friends and earn a small commission for every purchase made by them.</div>
    </div>
</div>

@stop