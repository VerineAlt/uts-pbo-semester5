<?php

use App\Support\KatalogBuku;

/**
 * @return array<string, mixed>
 */
function dataBukuValid(array $override = []): array
{
    return array_merge([
        'kode' => 'BK-100',
        'judul' => 'Algoritma dan Struktur Data',
        'penulis' => 'Rinaldi Munir',
        'penerbit' => 'Informatika',
        'tahun' => 2020,
        'kategori' => 'Pemrograman',
        'lokasi' => 'Rak C-09',
        'jumlah' => 3,
        'kondisi' => 'Baik',
        'deskripsi' => 'Buku pegangan kuliah.',
    ], $override);
}

it('mengarahkan halaman utama ke daftar buku', function () {
    $this->get('/')->assertRedirect(route('buku.index'));
});

it('memuat data contoh dan menampilkan kolom daftar buku', function () {
    $this->get(route('buku.index'))
        ->assertOk()
        ->assertSeeInOrder(['Kode', 'Judul Buku', 'Kategori', 'Lokasi', 'Jumlah', 'Kondisi', 'Aksi'])
        ->assertSee('Laskar Pelangi')
        ->assertSee('BK-001');
});

it('menampilkan form tambah dengan csrf', function () {
    $this->get(route('buku.create'))
        ->assertOk()
        ->assertSee('name="_token"', false)
        ->assertSee('name="judul"', false);
});

it('menyimpan buku baru ke session lalu redirect dengan pesan sukses', function () {
    $this->post(route('buku.store'), dataBukuValid(['kode' => 'bk-100']))
        ->assertRedirect(route('buku.index'))
        ->assertSessionHas('sukses');

    expect(session('buku'))->toHaveKey('BK-100');
    expect(session('buku')['BK-100']['judul'])->toBe('Algoritma dan Struktur Data');
});

it('menolak kode buku yang sudah dipakai', function () {
    $this->post(route('buku.store'), dataBukuValid(['kode' => 'bk-001']))
        ->assertSessionHasErrors('kode');
});

it('menolak input yang tidak valid', function () {
    $this->post(route('buku.store'), dataBukuValid([
        'judul' => '',
        'jumlah' => -1,
        'kondisi' => 'Hancur',
        'tahun' => 1500,
    ]))->assertSessionHasErrors(['judul', 'jumlah', 'kondisi', 'tahun']);

    expect(session('buku'))->toBeNull();
});

it('menampilkan detail buku berdasarkan kode', function () {
    $this->get(route('buku.show', 'BK-003'))
        ->assertOk()
        ->assertSee('Clean Code')
        ->assertSee('Robert C. Martin');
});

it('menampilkan form edit dengan nilai lama', function () {
    $this->get(route('buku.edit', 'BK-003'))
        ->assertOk()
        ->assertSee('value="Clean Code"', false)
        ->assertSee('name="_method"', false)
        ->assertSee('value="PUT"', false);
});

it('memperbarui buku tanpa mengubah kode', function () {
    $this->put(route('buku.update', 'BK-003'), dataBukuValid(['kode' => 'HACK', 'judul' => 'Clean Code Edisi 2']))
        ->assertRedirect(route('buku.index'))
        ->assertSessionHas('sukses');

    expect(session('buku')['BK-003']['judul'])->toBe('Clean Code Edisi 2');
    expect(session('buku'))->not->toHaveKey('HACK');
});

it('menampilkan nama lama dan baru pada pesan jika judul berubah', function () {
    $this->put(route('buku.update', 'BK-003'), dataBukuValid(['judul' => 'Clean Code Edisi 2']))
        ->assertSessionHas('sukses', 'Buku "Clean Code" -> "Clean Code Edisi 2" berhasil diperbarui.');
});

it('hanya menampilkan nama buku pada pesan jika judul tidak berubah', function () {
    $this->put(route('buku.update', 'BK-003'), dataBukuValid(['judul' => 'Clean Code', 'deskripsi' => 'Deskripsi baru.']))
        ->assertSessionHas('sukses', 'Buku "Clean Code" berhasil diperbarui.');
});

it('menghapus buku dari session', function () {
    $this->delete(route('buku.destroy', 'BK-002'))
        ->assertRedirect(route('buku.index'))
        ->assertSessionHas('sukses');

    expect(session('buku'))->not->toHaveKey('BK-002');
});

it('mengalihkan dengan pesan info jika kode tidak ditemukan', function () {
    $this->get(route('buku.show', 'TIDAK-ADA'))->assertRedirect(route('buku.index'))->assertSessionHas('info');
    $this->get(route('buku.edit', 'TIDAK-ADA'))->assertRedirect(route('buku.index'))->assertSessionHas('info');
    $this->put(route('buku.update', 'TIDAK-ADA'), dataBukuValid())->assertRedirect(route('buku.index'))->assertSessionHas('info');
    $this->delete(route('buku.destroy', 'TIDAK-ADA'))->assertRedirect(route('buku.index'))->assertSessionHas('info');
});

it('menampilkan pesan kosong saat semua buku dihapus', function () {
    $this->withSession(['buku' => []])
        ->get(route('buku.index'))
        ->assertOk()
        ->assertSee('Belum ada data buku tersimpan.');
});

it('mencari buku berdasarkan kata kunci dan kategori', function () {
    $this->get(route('buku.index', ['q' => 'sagan']))
        ->assertOk()
        ->assertSee('Kosmos')
        ->assertDontSee('Laskar Pelangi');

    $this->get(route('buku.index', ['kategori' => 'Pemrograman']))
        ->assertOk()
        ->assertSee('Clean Code')
        ->assertDontSee('Kosmos');
});

it('mengembalikan data contoh awal', function () {
    $this->withSession(['buku' => []])
        ->post(route('buku.reset'))
        ->assertRedirect(route('buku.index'));

    expect(session('buku'))->toBe(KatalogBuku::dataAwal());
});

it('mendefinisikan route create sebelum route dinamis kode', function () {
    $uri = collect(app('router')->getRoutes()->getRoutes())
        ->filter(fn ($route) => in_array('GET', $route->methods(), true) && str_starts_with($route->uri(), 'buku'))
        ->map->uri()
        ->values();

    expect($uri->search('buku/create'))->toBeLessThan($uri->search('buku/{kode}'));
});
