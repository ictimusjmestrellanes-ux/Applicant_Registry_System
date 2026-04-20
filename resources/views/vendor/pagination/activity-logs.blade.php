@if ($paginator->hasPages())
    <style>
        /* Compact rounded pagination (white pills with light border, black active) */
        .compact-pagination { display:flex; align-items:center; gap:6px; }
        .compact-pagination .page-item { margin: 0; }
        .compact-pagination .page-link {
            padding: 4px 8px;
            border-radius: 10px;
            min-width: 32px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            background: #fff;
            color: #0f172a;
            border: 1px solid rgba(15,23,42,0.06);
            box-shadow: none;
        }
        .compact-pagination .page-item + .page-item { margin-left: 6px; }
        .compact-pagination .page-item.active .page-link {
            background: #000;
            color: #fff;
            border-color: #000;
        }
        .compact-pagination .page-item.disabled .page-link {
            opacity: 0.45;
            cursor: default;
        }
        .compact-pagination .page-link span,
        .compact-pagination .page-link svg { display: inline-block; }
    </style>

    <nav aria-label="Activity log pagination">
        <ul class="pagination compact-pagination">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            @php
                $last = $paginator->lastPage();
                $current = $paginator->currentPage();
            @endphp

            {{-- Render compact: if few pages show all, otherwise show 1,2,3 ... last --}}
            @if ($last <= 5)
                @for ($i = 1; $i <= $last; $i++)
                    @if ($i == $current)
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor
            @else
                {{-- pages 1,2,3 always visible --}}
                @for ($i = 1; $i <= 3; $i++)
                    @if ($i == $current)
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor

                {{-- Ensure active page is visible when it's beyond 3 --}}
                @if ($current <= 3)
                    {{-- current already shown in 1..3 --}}
                    <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>

                @elseif ($current == 4)
                    {{-- show page 4 directly (no leading ellipsis) --}}
                    @if (4 == $current)
                        <li class="page-item active" aria-current="page"><span class="page-link">4</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url(4) }}">4</a></li>
                    @endif
                    @if ($last == 5)
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url(5) }}">5</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>
                    @endif

                @elseif ($current > 4 && $current < $last)
                    {{-- show ellipsis, the active page, then ellipsis/last as needed --}}
                    <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $current }}</span></li>

                    @if ($current == $last - 1)
                        {{-- adjacent to last, render last directly --}}
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>
                    @endif

                @elseif ($current == $last)
                    {{-- current is last --}}
                    <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $last }}</span></li>
                @endif
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-hidden="true">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
