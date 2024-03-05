<div class="form-outline mb-4">
    <input type="text" id="address" class="form-control form-control-lg @error('address') is-invalid @enderror"
        name="address" required autofocus autocomplete="address" />
    <input type="hidden" name="latlng" id="ll">
    <label class="form-label" for="address">{{ __('Alamat') }}</label>
    @error('address')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div id="map" style="height: 300px" class="mb-4"></div>
