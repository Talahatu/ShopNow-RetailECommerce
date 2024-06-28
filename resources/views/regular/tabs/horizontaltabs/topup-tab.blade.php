<div class="card ">
    <div class="card-body">
        <h1>Saldo Anda</h1>
        <div class="balance">
            <h2 id="balance" class="text-dark text-muted">Rp {{ number_format(Auth::user()->saldo, 0, ',', '.') }}</h2>
            <input type="hidden" name="saldo" id="balance-val" value="{{ Auth::user()->saldo }}">
        </div><br>

        <h1>Tambahkan Saldo</h1>
        <input type="number" name="topup" id="topup" class="form-control mt-4 mb-2" value="1000" step="1000"
            min="1000">
        <button class="btn btn-dark" id="btnTopup">Tambahkan</button>
    </div>
</div>
