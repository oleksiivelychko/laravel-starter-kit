@php
    /** @var \App\Models\OrderItem $orderItem */
@endphp
<form action="{{ $action }}" method="post">
    {{ csrf_field() }}

    @if ($orderItem->exists)
        {{ method_field('put') }}
    @endif

    <div class="row mt-2">
        <div class="col-md-6 col-12">
            <div class="mb-3">
                <label class="form-label" for="price">{{ __('dashboard.price') }}<span class="required">*</span></label>
                <input
                    type="number"
                    step="0.1"
                    class="form-control @error('price') is-invalid @enderror"
                    id="price"
                    name="price"
                    placeholder="0.1"
                    value="{{ $orderItem->price ?: old('price') }}"
                >
                @error('price')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="quantity">{{ __('dashboard.quantity') }}<span class="required">*</span></label>
                <input
                    type="number"
                    step="1"
                    class="form-control @error('quantity') is-invalid @enderror"
                    id="quantity"
                    name="quantity"
                    placeholder="0"
                    value="{{ $orderItem->quantity ?: old('quantity') }}"
                >
                @error('quantity')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="product_id">{{ __('dashboard.product') }}<span class="required">*</span></label>
                <select
                    class="live-search @error('product_id') is-invalid @enderror"
                    id="product_id"
                    name="product_id"
                    data-entity-name="product"
                    data-entity-value="{{ $orderItem->product_id ?: 0 }}"
                    data-entity-text="{{ $orderItem->product_id ? $orderItem->product->translate('name') : '' }}"
                ></select>
                @error('product_id')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="{{ $orderItem->id ?? 0 }}">

    <div class="row mt-4 mb-2 justify-content-end">
        <div class="col-12 col-md-3 text-end">
            @component('components.buttons.save')@endcomponent
        </div>
    </div>
</form>

<div class="row gy-2">
    <div class="col-12 col-md-3">
        @if ($orderItem->exists)
            <form action="{{ route('order-item.destroy', ['order_item' => $orderItem, 'locale' => $currentLocale]) }}" method="post">
                @csrf
                @method('delete')
                @component('components.buttons.delete')@endcomponent
            </form>
        @endif
    </div>
</div>
