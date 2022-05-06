@if (session('status'))
    <div id="alert" class="alert {{ $alert }}" role="alert">
        {{ session('status') }}
        @php
            session()->forget('status');
        @endphp
    </div>
@endif
