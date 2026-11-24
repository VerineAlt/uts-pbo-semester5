@extends('layouts.app')

@section('judul', 'Detail Buku')
@section('subjudul', 'Informasi lengkap satu buku koleksi perpustakaan.')

@section('konten')
    <div class="panel">
        <div class="kepala-detail">
            <div>
                <code>{{ $buku['kode'] }}</code>
                <h2>{{ $buku['judul'] }}</h2>
                <p class="penulis">oleh {{ $buku['penulis'] }}</p>
            </div>
            <span class="lencana {{ \App\Enums\KondisiBuku::from($buku['kondisi'])->kelasLencana() }}">
                {{ $buku['kondisi'] }}
            </span>
        </div>

        <dl class="detail">
            <div><dt>Kategori</dt><dd>{{ $buku['kategori'] }}</dd></div>
            <div><dt>Penerbit</dt><dd>{{ $buku['penerbit'] ?: '-' }}</dd></div>
            <div><dt>Tahun Terbit</dt><dd>{{ $buku['tahun'] }}</dd></div>
            <div><dt>Lokasi Rak</dt><dd>{{ $buku['lokasi'] }}</dd></div>
            <div><dt>Jumlah Stok</dt><dd>{{ $buku['jumlah'] }}</dd></div>
            <div class="lebar"><dt>Deskripsi</dt><dd>{{ $buku['deskripsi'] ?: 'Belum ada deskripsi.' }}</dd></div>
        </dl>

        <div class="bilah-aksi">
            <a href="{{ route('buku.index') }}" class="tombol tombol-garis">&larr; Kembali</a>
            <a href="{{ route('buku.edit', $buku['kode']) }}" class="tombol tombol-sekunder">Edit</a>
            <form method="POST" action="{{ route('buku.destroy', $buku['kode']) }}"
                  onsubmit="return confirm('Hapus buku &quot;{{ addslashes($buku['judul']) }}&quot;? Tindakan ini tidak bisa dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="tombol tombol-bahaya">Hapus</button>
            </form>
        </div>
    </div>
@endsection
