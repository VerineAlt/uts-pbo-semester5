@extends('layouts.app')

@section('judul', 'Daftar Buku')
@section('subjudul', 'Kelola koleksi buku perpustakaan: lihat, cari, tambah, ubah, dan hapus.')

@section('konten')
    <section class="statistik">
        <div class="kartu-statistik">
            <span class="angka">{{ $totalJudul }}</span>
            <span class="label">Judul Buku</span>
        </div>
        <div class="kartu-statistik">
            <span class="angka">{{ $totalEksemplar }}</span>
            <span class="label">Total Stok Buku</span>
        </div>
        <div class="kartu-statistik peringatan">
            <span class="angka">{{ $totalPerluPerbaikan }}</span>
            <span class="label">Perlu Perbaikan</span>
        </div>
    </section>

    <div class="panel">
        <form method="GET" action="{{ route('buku.index') }}" class="bilah-filter">
            <input type="search" name="q" value="{{ $kataKunci }}" placeholder="Cari kode, judul, atau penulis...">
            <select name="kategori">
                <option value="">Semua kategori</option>
                @foreach ($daftarKategori as $kategori)
                    <option value="{{ $kategori }}" @selected($kategori === $kategoriDipilih)>{{ $kategori }}</option>
                @endforeach
            </select>
            <select name="urut">
                <option value="kode" @selected($urutan === 'kode')>Urutkan: Kode</option>
                <option value="judul" @selected($urutan === 'judul')>Urutkan: Judul</option>
                <option value="jumlah" @selected($urutan === 'jumlah')>Urutkan: Jumlah terbanyak</option>
            </select>
            <button type="submit" class="tombol tombol-utama">Terapkan</button>
            @if ($kataKunci !== '' || $kategoriDipilih !== '' || $urutan !== 'kode')
                <a href="{{ route('buku.index') }}" class="tombol tombol-garis">Reset filter</a>
            @endif
        </form>

        <div class="bilah-aksi">
            <a href="{{ route('buku.create') }}" class="tombol tombol-utama">+ Tambah Buku</a>
            <form method="POST" action="{{ route('buku.reset') }}"
                  onsubmit="return confirm('Kembalikan semua data ke contoh awal? Perubahan Anda akan hilang.')">
                @csrf
                <button type="submit" class="tombol tombol-garis">Kembalikan Data Contoh</button>
            </form>
        </div>

        @if ($daftarBuku->isEmpty())
            <div class="kosong">
                @if ($totalJudul === 0)
                    <p>Belum ada data buku tersimpan.</p>
                    <a href="{{ route('buku.create') }}" class="tombol tombol-utama">Tambah buku pertama</a>
                @else
                    <p>Tidak ada buku yang cocok dengan pencarian Anda.</p>
                @endif
            </div>
        @else
            <div class="tabel-gulir">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Judul Buku</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th class="rata-kanan">Jumlah</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarBuku as $buku)
                            <tr>
                                <td><code>{{ $buku['kode'] }}</code></td>
                                <td>
                                    <strong>{{ $buku['judul'] }}</strong>
                                    <small>{{ $buku['penulis'] }}</small>
                                </td>
                                <td>{{ $buku['kategori'] }}</td>
                                <td>{{ $buku['lokasi'] }}</td>
                                <td class="rata-kanan">{{ $buku['jumlah'] }}</td>
                                <td>
                                    <span class="lencana {{ \App\Enums\KondisiBuku::from($buku['kondisi'])->kelasLencana() }}">
                                        {{ $buku['kondisi'] }}
                                    </span>
                                </td>
                                <td class="sel-aksi">
                                    <a href="{{ route('buku.show', $buku['kode']) }}" class="tombol tombol-kecil tombol-garis">Detail</a>
                                    <a href="{{ route('buku.edit', $buku['kode']) }}" class="tombol tombol-kecil tombol-sekunder">Edit</a>
                                    <form method="POST" action="{{ route('buku.destroy', $buku['kode']) }}"
                                          onsubmit="return confirm('Hapus buku &quot;{{ addslashes($buku['judul']) }}&quot;? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tombol tombol-kecil tombol-bahaya">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="catatan-tabel">Menampilkan {{ $daftarBuku->count() }} dari {{ $totalJudul }} judul.</p>
        @endif
    </div>
@endsection
