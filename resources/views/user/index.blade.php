@extends('master.main')

@section('title', 'Account index')

@section('content')

@include('includes.components.menuaccount')
<div class="content-profile">
    @include('includes.flash.validation')
    @include('includes.flash.success')
    @include('includes.flash.error')
    <div class="h2 mb-15">Account index</div>
    <div class="container mb-20">
        <div class="info-wrapper float-right">
            <div class="info-folder">
                <div class="info-icon">?</div>
                <div class="info-message">Transfer some balance to other market members. We do not require a minimum amount and do not charge fees. The amount entered must be in US Dollars.</div>
            </div>
        </div>
        <div class="h3 mb-10">Transfer moneros to another member</div>
        <form action="{{ route('post.transfer') }}" method="post">
            @csrf
            <label for="username">username</label>
            <input type="text" id="username" name="username">
            <label for="value">value</label>
            <input type="text" id="value" name="value" style="width: 60px" placeholder="in USD">
            <label for="pin">PIN</label>
            <input type="password" id="pin" name="pin" maxlength="6" style="width: 60px">
            <button type="submit">transfer</button>
        </form>
    </div>
    <div class="container">
        <div class="info-wrapper float-right">
            <div class="info-folder">
                <div class="info-icon">?</div>
                <div class="info-message">Withdraw the balance available in your account anytime you want. We do not require a minimum amount and do not charge fees. The amount entered must be in US Dollars.</div>
            </div>
        </div>    
        <div class="h3 mb-10">Withdraw moneros</div>
        <form action="{{ route('post.withdraw') }}" method="post">
            @csrf
            <label for="monero_wallet_address">monero wallet address</label>
            <input type="text" id="monero_wallet_address" name="monero_wallet_address" style="font-family: Courier; font-size: 90%; background-color: #fff; color: #000; font-weight: bold; width: 280px">
            <label for="value">value</label>
            <input type="text" id="value" name="value" style="width: 60px" placeholder="in USD">
            <label for="pin">PIN</label>
            <input type="password" id="pin" name="pin" maxlength="6" style="width: 60px">
            <button type="submit">withdraw</button>
        </form>
    </div>
</div>

@stop