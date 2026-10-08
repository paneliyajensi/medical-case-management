@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; gap: 0.35rem; align-items: center;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span style="padding: 0.35rem 0.7rem; font-size: 0.825rem; color: var(--slate-300); border: 1px solid var(--slate-200); border-radius: var(--radius-sm); cursor: not-allowed; background: white;">
                &larr; Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="padding: 0.35rem 0.7rem; font-size: 0.825rem; color: var(--slate-700); border: 1px solid var(--slate-300); border-radius: var(--radius-sm); text-decoration: none; background: white; font-weight: 500;">
                &larr; Prev
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span style="padding: 0.35rem 0.6rem; font-size: 0.825rem; color: var(--slate-500);">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding: 0.35rem 0.75rem; font-size: 0.825rem; color: white; background-color: var(--primary); border: 1px solid var(--primary); border-radius: var(--radius-sm); font-weight: 700;">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" style="padding: 0.35rem 0.75rem; font-size: 0.825rem; color: var(--slate-700); border: 1px solid var(--slate-300); border-radius: var(--radius-sm); text-decoration: none; background: white; font-weight: 500;">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="padding: 0.35rem 0.7rem; font-size: 0.825rem; color: var(--slate-700); border: 1px solid var(--slate-300); border-radius: var(--radius-sm); text-decoration: none; background: white; font-weight: 500;">
                Next &rarr;
            </a>
        @else
            <span style="padding: 0.35rem 0.7rem; font-size: 0.825rem; color: var(--slate-300); border: 1px solid var(--slate-200); border-radius: var(--radius-sm); cursor: not-allowed; background: white;">
                Next &rarr;
            </span>
        @endif
    </nav>
@endif
