<footer>
   <span class="footer-link float-left" style="color: black">1 XMR ≅ {{ \App\Tools\Converter::currencyConverter(\App\Tools\Converter::moneroLastPrice(), auth()->user()->currency) }} {{ \App\Tools\Converter::getSymbol(auth()->user()->currency) }}</span><a href="#">wiki</a> | <a href="#">forum</a> | <a href="{{ route('support') }}" class="footer-link">support</a>
</footer>

