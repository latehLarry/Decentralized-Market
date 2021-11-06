@php
	$totalPages = 0
@endphp

@if($paginator->hasPages())
	<div class="bar" style="border: none">
        @if($paginator->onFirstPage())
        	<span>First</span>
       	@else
		 	<a href="{{ $paginator->url(1) }}">First</a>
		@endif
        @if($paginator->onFirstPage())
           	<span aria-hidden="true">&lsaquo;&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">&lsaquo;&lsaquo;</a>
        @endif
        @foreach($elements as $element)
        	@if(is_string($element))
            	<span>{{ $element }}</span>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                	@php
                		$totalPages += 1
                	@endphp
                    @if($page == $paginator->currentPage())
                    	<span>{{ $page }}</span></li>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">&rsaquo;&rsaquo;</a>
        @else
            <span aria-hidden="true">&rsaquo;&rsaquo;</span>
        @endif
        @if($paginator->hasMorePages())
        	<a href="{{ $paginator->url($totalPages) }}">Last</a>
       	@else
		 	<span>Last</span>
		@endif
	</div>
@endif