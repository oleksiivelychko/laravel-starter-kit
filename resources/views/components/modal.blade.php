<div class="modal fade" id="modalWindow" tabindex="-1" aria-labelledby="modalWindowLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalWindowLabel">@yield('modal_title')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                @yield('modal_body')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.modal.close') }}</button>
                <a href="@yield('modal_url')" type="button" class="btn btn-primary">{{ __('dashboard.modal.perform') }}</a>
            </div>
        </div>
    </div>
</div>
