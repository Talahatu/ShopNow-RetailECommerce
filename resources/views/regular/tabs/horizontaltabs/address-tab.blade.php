<!-- Button trigger modal -->
<button type="button" class="btn btn-dark btn-lg mb-4 mt-4" id="btnAdd" data-bs-toggle="modal"
    data-bs-target="#exampleModal" data-bs-title="Add new address">
    Add new address
</button>

<div class="list-group">
    @foreach (Auth::user()->addresses as $item)
        <a href="#" class="list-group-item list-group-item-action {{ $item->current == 1 ? 'active' : '' }}"
            aria-current="true">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">{{ $item->name }} <span class="badge bg-success">{{ __('Home') }}</span></h5>
                <small>{{ $item->current == 1 ? 'Current' : '' }}</small>
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-info btn-update">Ubah</button>
                @if ($item->current != 1)
                    <button type="button" class="btn btn-danger btn-delete" id="deleteAddress">Hapus</button>
                    <button type="button" class="btn btn-outline-info btn-sm">Set as current address</button>
                @endif
                <input type="hidden" class="dia" attr-dia="{{ $item->id }}">
            </div>
        </a>
    @endforeach
    @isset($shop)
        <a href="#" class="list-group-item list-group-item-warning" aria-current="true">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">{{ $shop->address }} <span class="badge bg-warning">{{ __('Shop') }}</span></h5>
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-info btn-update">Ubah</button>
                <input type="hidden" class="dia" attr-dia="{{ $shop->id }}">
            </div>
        </a>
    @endisset
</div>
