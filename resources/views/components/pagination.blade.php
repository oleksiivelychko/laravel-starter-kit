@if ($paginator->hasPages())
    <nav aria-label="...">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item {{ $page === $paginator->currentPage() ? 'active' : '' }}">
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
@endif
