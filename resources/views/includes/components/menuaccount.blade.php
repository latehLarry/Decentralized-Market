<div class="content-sidebar">
    <div class="notices">
        <span class="notices-header">Account links</span>
        <ul class="notices-list">
            <li><a href="{{ route('accountindex') }}">Account index</a></li>
            <li><a href="{{ route('statistics') }}">Account statistics</a></li>
            <li><a href="{{ route('history') }}">Account history</a></li>
            <li><a href="{{ route('settings') }}">Settings</a></li>
            <li><a href="{{ route('affiliate') }}">Affiliate system</a></li>
            @seller
            <li><a href="{{ route('seller.dashboard') }}">Seller dashboard</a></li>
            <li><a href="{{ route('seller', ['seller' => auth()->user()->username]) }}">Seller profile</a></li>
            @else
            <li><a href="{{ route('becomeseller') }}">Become a seller</a></li>
            @endif
            <li><a href="{{ route('support') }}">Support</a></li>
        </ul>
    </div>
</div>