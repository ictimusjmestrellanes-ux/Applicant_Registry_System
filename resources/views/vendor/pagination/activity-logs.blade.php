@if ($paginator->hasPages())
    <style>
        /* Compact rounded pagination (white pills with light border, black active) */
        .compact-pagination { display:flex; align-items:center; gap:6px; }
        .compact-pagination .page-item { margin: 0; }
        .compact-pagination .page-link {
            padding: 6px 10px;
            border-radius: 10px;
            min-width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.92rem;
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

        /* Center pagination on small screens */
        @media (max-width: 640px) {
            .compact-pagination { justify-content: center; flex-wrap: wrap; }
        }
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

            @if ($last <= 5)
                @for ($i = 1; $i <= $last; $i++)
                    @if ($i == $current)
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor
            @else
                {{-- Always show first two pages --}}
                @for ($i = 1; $i <= 2; $i++)
                    @if ($i == $current)
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor

                @if ($current <= 3)
                    {{-- Show page 3, ellipsis, last --}}
                    @if (3 == $current)
                        <li class="page-item active" aria-current="page"><span class="page-link">3</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url(3) }}">3</a></li>
                    @endif
                    <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>

                @elseif ($current >= $last - 2)
                    {{-- show last three pages --}}
                    <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                    @for ($i = $last - 2; $i <= $last; $i++)
                        @if ($i == $current)
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor

                @else
                    {{-- middle: show ellipsis, current, ellipsis, last --}}
                    <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $current }}</span></li>
                    <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>
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
