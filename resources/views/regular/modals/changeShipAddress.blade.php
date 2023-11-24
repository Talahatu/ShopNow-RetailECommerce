<div class="list-group">
    @foreach ($addresses as $item)
        <a href="#"
            class="list-group-item list-group-item-action {{ $item->id == $id ? 'active' : '' }} address-item"
            aria-current="true" attr-int={{ $item->id }}>
            <div class="d-flex w-100 justify-content-between address-content">
                <p class="mb-1 address-name">{{ $item->name }}</p>
            </div>
            <input type="hidden" class="dia" attr-dia="{{ $item->id }}">
        </a>
    @endforeach
</div>
