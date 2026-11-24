@if (session('sukses'))
    <div class="pesan pesan-sukses" role="status">{{ session('sukses') }}</div>
@endif

@if (session('info'))
    <div class="pesan pesan-info" role="status">{{ session('info') }}</div>
@endif

@if (session('gagal'))
    <div class="pesan pesan-gagal" role="alert">{{ session('gagal') }}</div>
@endif

@if ($errors->any())
    <div class="pesan pesan-gagal" role="alert">
        <strong>Data belum bisa disimpan:</strong>
        <ul>
            @foreach ($errors->all() as $kesalahan)
                <li>{{ $kesalahan }}</li>
            @endforeach
        </ul>
    </div>
@endif
