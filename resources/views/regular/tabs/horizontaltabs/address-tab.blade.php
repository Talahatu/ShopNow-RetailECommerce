<!-- Button trigger modal -->
<button type="button" class="btn btn-dark btn-lg mb-4 mt-4" id="btnAdd" data-bs-toggle="modal"
    data-bs-target="#exampleModal" data-bs-title="Add new address">
    Add new address
</button>

<div class="list-group" id="list-address">
    @isset($shop)
        <a href="#" class="list-group-item list-group-item-warning" aria-current="true" addr-type="shop">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1 address-name">{{ $shop->address }} <span class="badge bg-warning">{{ __('Shop') }}</span>
                </h5>
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-info btn-update" data-bs-title="Update Shop Address"
                    data-bs-toggle="modal" data-bs-target="#exampleModal">Ubah</button>
            </div>
            <input type="hidden" class="dia" attr-dia="{{ $shop->id }}">
        </a>
    @endisset
    @foreach (Auth::user()->addresses as $item)
        <a href="#"
            class="list-group-item list-group-item-action {{ $item->current == 1 ? 'active' : '' }} address-item"
            aria-current="true" attr-int={{ $item->id }}>
            <div class="d-flex w-100 justify-content-between address-content">
                <h5 class="mb-1 address-name">{{ $item->name }} <span
                        class="badge bg-success">{{ __('Home') }}</span>
                </h5>
                @if ($item->current == 1)
                    <small>Current</small>
                @else
                    <button type="button" class="btn btn-outline-info btn-sm set-current-addr">Set as current
                        address</button>
                @endif
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-info btn-update" data-bs-toggle="modal"
                    data-bs-title="Update address" data-bs-target="#exampleModal">Ubah</button>
                @if ($item->current != 1)
                    <button type="button" class="btn btn-danger btn-delete" id="deleteAddress">Hapus</button>
                @endif
            </div>
            <input type="hidden" class="dia" attr-dia="{{ $item->id }}">
        </a>
    @endforeach
</div>
