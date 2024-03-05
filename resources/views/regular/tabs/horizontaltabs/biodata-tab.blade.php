<form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="shopPhotoContainer mb-4">
        <div class="mb-4 d-flex justify-content-center">
            <img id="selectedAvatar"
                src="{{ file_exists(public_path('profileimages/' . Auth::user()->profilePicture)) ? asset('profileimages/' . Auth::user()->profilePicture) : 'https://mdbootstrap.com/img/Photos/Others/placeholder-avatar.jpg' }} "
                alt="example placeholder" class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover;" />
        </div>
        <div class="d-flex justify-content-center">
            <div class="btn btn-dark btn-rounded">
                <label class="form-label text-white m-1" for="image">Pilih Gambar</label>
                <input type="file" name="image" class="form-control d-none" id="image" />
                @error('image')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-outline mb-4">
        <input type="text" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
            name="name" value="{{ Auth::user()->name }}" required autofocus autocomplete="name" />
        <label class="form-label" for="name">{{ __('Nama') }}</label>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-outline mb-4">
        <input type="text" id="username"
            class="form-control form-control-lg @error('username') is-invalid @enderror" name="username"
            value="{{ Auth::user()->username }}" required autofocus autocomplete="username" />
        <label class="form-label" for="username">{{ __('Nama Alias') }}</label>
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="mb-4">
        <input type="text" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
            name="email" value="{{ Auth::user()->email }}" disabled />
        <a href="{{ route('change.email.show') }}" class="btn btn-info mt-2">Ubah Email</a>
        @if (!Auth::user()->hasVerifiedEmail())
            <a href="{{ route('verify.logged.email') }}" class="btn btn-danger mt-2">Verifikasi Email</a>
        @endif
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-outline mb-4">
        <input type="text" id="phoneNumber"
            class="form-control form-control-lg @error('phoneNumber') is-invalid @enderror" name="phoneNumber"
            value="{{ Auth::user()->phoneNumber }}" />
        <label class="form-label" for="phoneNumber">{{ __('Nomor Telepon') }}</label>
        @error('phoneNumber')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <h5>Gender:</h5>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="gender" id="man" value="man"
            {{ Auth::user()->gender == 'man' ? 'Checked' : '' }}>
        <label class="form-check-label" for="man">Laki-Laki</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="gender" id="woman" value="woman"
            {{ Auth::user()->gender == 'woman' ? 'Checked' : '' }}>
        <label class="form-check-label" for="woman">Perempuan</label>
    </div>
    <div class="form-check form-check-inline mb-4">
        <input class="form-check-input" type="radio" name="gender" id="other" value="other"
            {{ Auth::user()->gender == 'other' ? 'Checked' : '' }}>
        <label class="form-check-label" for="other">Lainnya</label>
    </div>

    <div class="pt-1 mb-4">
        <button class="btn btn-dark btn-lg btn-block" type="submit">Ubah</button>
    </div>
</form>
