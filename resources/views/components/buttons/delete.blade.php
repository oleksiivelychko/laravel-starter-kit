<button type="{{ $type ?? 'submit' }}" class="btn btn-sm btn-outline-danger w-100">
    <i class="bi bi-trash"></i>
    &nbsp;{{ $title ?? __('dashboard.delete') }}
</button>
