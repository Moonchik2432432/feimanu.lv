@if ($paginator->hasPages())
    <nav class="my-pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="page disabled">‹</span>
        @else
            <a class="page" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page dots">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page active">{{ $page }}</span>
                    @else
                        <a class="page" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a class="page" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
        @else
            <span class="page disabled">›</span>
        @endif
    </nav>
@endif
