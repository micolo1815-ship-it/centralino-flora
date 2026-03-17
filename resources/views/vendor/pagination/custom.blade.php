@if ($paginator->hasPages())

    <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">

        {{-- Previous Page --}}
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md"
               href="{{ $paginator->onFirstPage() ? '#' : $paginator->previousPageUrl() }}">
                <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
            </a>
        </li>

        {{-- Page Numbers --}}
        @foreach ($elements as $element)

            {{-- "..." --}}
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link">{{ $element }}</span>
                </li>
            @endif

            {{-- Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li class="page-item">
                        <a class="page-link fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md
                            {{ $page == $paginator->currentPage()
                                ? 'bg-primary-600 text-white'
                                : 'bg-neutral-200 text-secondary-light' }}"
                           href="{{ $url }}">
                            {{ $page }}
                        </a>
                    </li>
                @endforeach
            @endif

        @endforeach

        {{-- Next Page --}}
        <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md"
               href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}">
                <iconify-icon icon="ep:d-arrow-right"></iconify-icon>
            </a>
        </li>

    </ul>
@endif
