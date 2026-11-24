# Pustaka Kampus - Manajemen Buku Perpustakaan

Aplikasi CRUD buku perpustakaan untuk UTS Pemrograman Berorientasi Objek (Laravel 13). Tanpa database dan tanpa login: seluruh data disimpan di **session** (driver `file`) dengan pola **Route → Controller → Blade**.

## Fitur

- Daftar buku (Kode, Judul, Kategori, Lokasi, Jumlah, Kondisi, Aksi) dengan statistik ringkas
- Detail, tambah, edit (nilai lama terisi otomatis), dan hapus dengan konfirmasi
- Validasi input (Form Request) dan pesan sukses/info/gagal
- Kode buku tidak ditemukan: redirect ke daftar dengan pesan informasi
- Tambahan: pencarian, filter kategori, pengurutan, dan tombol "Kembalikan Data Contoh"

## Route

| Method    | URI                | Nama route    | Aksi    |
|-----------|--------------------|---------------|---------|
| GET       | /buku              | buku.index    | index   |
| GET       | /buku/create       | buku.create   | create  |
| POST      | /buku              | buku.store    | store   |
| GET       | /buku/{kode}       | buku.show     | show    |
| GET       | /buku/{kode}/edit  | buku.edit     | edit    |
| PUT/PATCH | /buku/{kode}       | buku.update   | update  |
| DELETE    | /buku/{kode}       | buku.destroy  | destroy |
| POST      | /buku/reset        | buku.reset    | reset (tambahan) |

Route statis `/buku/create` didefinisikan sebelum route dinamis `/buku/{kode}`. Lihat `routes/web.php`.

## Menjalankan Proyek

Prasyarat: PHP 8.3+ dan Composer.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Buka http://127.0.0.1:8000.

Pastikan `SESSION_DRIVER=file` di `.env` (sudah diatur di `.env.example`) dan folder `storage/framework/sessions` dapat ditulisi.

## Pengujian

```bash
php artisan test
php artisan route:list --path=buku
```

## Struktur File Utama

- `routes/web.php` - definisi route bernama
- `app/Http/Controllers/BukuController.php` - alur CRUD dan pengelolaan session
- `app/Http/Requests/SimpanBukuRequest.php` - validasi input
- `app/Enums/KondisiBuku.php` dan `app/Support/KatalogBuku.php` - pilihan kondisi, kategori, dan data contoh
- `resources/views/layouts/app.blade.php` - layout utama
- `resources/views/buku/` - halaman index, show, create, edit, dan form bersama
- `public/css/perpustakaan.css` - gaya tampilan
- `tests/Feature/BukuTest.php` - pengujian fitur
