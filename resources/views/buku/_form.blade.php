@php
    $nilaiAwal = $buku ?? [];
@endphp

<div class="kisi-form">
    <div class="kolom">
        <label for="kode">Kode Buku</label>
        @if ($sedangEdit)
            <input type="text" id="kode" value="{{ $nilaiAwal['kode'] }}" readonly class="terkunci">
            <small>Kode adalah kunci data dan tidak dapat diubah.</small>
        @else
            <input type="text" id="kode" name="kode" value="{{ old('kode') }}" maxlength="20" placeholder="Contoh: BK-009" required>
        @endif
    </div>

    <div class="kolom">
        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori" required>
            <option value="">-- Pilih kategori --</option>
            @foreach ($daftarKategori as $kategori)
                <option value="{{ $kategori }}" @selected(old('kategori', $nilaiAwal['kategori'] ?? '') === $kategori)>{{ $kategori }}</option>
            @endforeach
        </select>
    </div>

    <div class="kolom lebar">
        <label for="judul">Judul Buku</label>
        <input type="text" id="judul" name="judul" value="{{ old('judul', $nilaiAwal['judul'] ?? '') }}" maxlength="150" required>
    </div>

    <div class="kolom">
        <label for="penulis">Penulis</label>
        <input type="text" id="penulis" name="penulis" value="{{ old('penulis', $nilaiAwal['penulis'] ?? '') }}" maxlength="100" required>
    </div>

    <div class="kolom">
        <label for="penerbit">Penerbit <span class="opsional">(opsional)</span></label>
        <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit', $nilaiAwal['penerbit'] ?? '') }}" maxlength="100">
    </div>

    <div class="kolom">
        <label for="tahun">Tahun Terbit</label>
        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $nilaiAwal['tahun'] ?? '') }}" min="1900" max="{{ now()->year }}" required>
    </div>

    <div class="kolom">
        <label for="lokasi">Lokasi Rak</label>
        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $nilaiAwal['lokasi'] ?? '') }}" maxlength="50" placeholder="Contoh: Rak A-01" required>
    </div>

    <div class="kolom">
        <label for="jumlah">Jumlah Stok</label>
        <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', $nilaiAwal['jumlah'] ?? 1) }}" min="0" max="9999" required>
    </div>

    <div class="kolom">
        <label for="kondisi">Kondisi</label>
        <select id="kondisi" name="kondisi" required>
            @foreach ($daftarKondisi as $kondisi)
                <option value="{{ $kondisi->value }}" @selected(old('kondisi', $nilaiAwal['kondisi'] ?? 'Baik') === $kondisi->value)>{{ $kondisi->value }}</option>
            @endforeach
        </select>
    </div>

    <div class="kolom lebar">
        <label for="deskripsi">Deskripsi <span class="opsional">(opsional)</span></label>
        <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="500">{{ old('deskripsi', $nilaiAwal['deskripsi'] ?? '') }}</textarea>
    </div>
</div>

<div class="bilah-aksi">
    <a href="{{ route('buku.index') }}" class="tombol tombol-garis">Batal</a>
    <button type="submit" class="tombol tombol-utama">{{ $sedangEdit ? 'Simpan Perubahan' : 'Simpan Buku' }}</button>
</div>
