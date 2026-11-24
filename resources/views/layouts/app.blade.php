<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Beranda') | Pustaka Kampus</title>
    <link rel="stylesheet" href="{{ asset('css/perpustakaan.css') }}">
</head>
<body>
    <header class="kepala">
        <div class="wadah kepala-isi">
            <a href="{{ route('buku.index') }}" class="merek">
                <span class="merek-ikon">&#128214;</span>
                <span>Pustaka Kampus</span>
            </a>
            <nav class="navigasi">
                <a href="{{ route('buku.index') }}" @class(['aktif' => request()->routeIs('buku.index')])>Daftar Buku</a>
                <a href="{{ route('buku.create') }}" @class(['aktif' => request()->routeIs('buku.create')])>Tambah Buku</a>
            </nav>
        </div>
    </header>

    <main class="wadah konten">
        <div class="judul-halaman">
            <h1>@yield('judul')</h1>
            @hasSection('subjudul')
                <p>@yield('subjudul')</p>
            @endif
        </div>

        @include('partials.pesan')

        @yield('konten')
    </main>

    <footer class="kaki">
        <div class="wadah">
            Pustaka Kampus &middot; Sistem Manajemen Perpustakaan Buku &middot; UTS Pemrograman Berorientasi Objek
        </div>
    </footer>
</body>
</html>
