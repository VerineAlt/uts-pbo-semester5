<?php

namespace App\Support;

use App\Enums\KondisiBuku;

class KatalogBuku
{
    /**
     * Daftar kategori buku yang dapat dipilih pada form.
     *
     * @return list<string>
     */
    public static function daftarKategori(): array
    {
        return [
            'Fiksi',
            'Non-Fiksi',
            'Sains & Teknologi',
            'Pemrograman',
            'Sejarah',
            'Biografi',
            'Pendidikan',
            'Anak-anak',
        ];
    }

    /**
     * Data awal yang dimuat ke session saat aplikasi pertama kali dibuka.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function dataAwal(): array
    {
        $contoh = [
            ['BK-001', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 'Fiksi', 'Rak A-01', 5, KondisiBuku::Baik, 'Kisah sepuluh anak Belitung yang berjuang menempuh pendidikan di sekolah sederhana.'],
            ['BK-002', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 'Sejarah', 'Rak B-03', 3, KondisiBuku::RusakRingan, 'Novel sejarah tentang Minke di masa kolonial Hindia Belanda.'],
            ['BK-003', 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 'Pemrograman', 'Rak C-02', 4, KondisiBuku::Baik, 'Panduan menulis kode yang bersih, mudah dibaca, dan mudah dirawat.'],
            ['BK-004', 'Sapiens', 'Yuval Noah Harari', 'Gramedia', 2017, 'Non-Fiksi', 'Rak B-01', 2, KondisiBuku::Baik, 'Sejarah singkat umat manusia dari zaman batu hingga era modern.'],
            ['BK-005', 'Habibie & Ainun', 'B.J. Habibie', 'THC Mandiri', 2010, 'Biografi', 'Rak D-04', 6, KondisiBuku::Baik, 'Kisah cinta dan perjalanan hidup Presiden ke-3 Republik Indonesia.'],
            ['BK-006', 'Pemrograman Web dengan Laravel', 'Tim Penulis Informatika', 'Informatika', 2023, 'Pemrograman', 'Rak C-05', 8, KondisiBuku::Baik, 'Pengantar membangun aplikasi web menggunakan framework Laravel.'],
            ['BK-007', 'Kosmos', 'Carl Sagan', 'Gramedia', 2013, 'Sains & Teknologi', 'Rak E-02', 1, KondisiBuku::RusakBerat, 'Perjalanan memahami alam semesta dan tempat manusia di dalamnya.'],
            ['BK-008', 'Si Kancil dan Buaya', 'Tim Redaksi', 'Balai Pustaka', 2015, 'Anak-anak', 'Rak F-01', 10, KondisiBuku::Baik, 'Dongeng klasik tentang kecerdikan Si Kancil.'],
        ];

        $data = [];

        foreach ($contoh as [$kode, $judul, $penulis, $penerbit, $tahun, $kategori, $lokasi, $jumlah, $kondisi, $deskripsi]) {
            $data[$kode] = [
                'kode' => $kode,
                'judul' => $judul,
                'penulis' => $penulis,
                'penerbit' => $penerbit,
                'tahun' => $tahun,
                'kategori' => $kategori,
                'lokasi' => $lokasi,
                'jumlah' => $jumlah,
                'kondisi' => $kondisi->value,
                'deskripsi' => $deskripsi,
            ];
        }

        return $data;
    }
}
