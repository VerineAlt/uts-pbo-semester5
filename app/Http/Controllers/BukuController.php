<?php

namespace App\Http\Controllers;

use App\Enums\KondisiBuku;
use App\Http\Requests\SimpanBukuRequest;
use App\Support\KatalogBuku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class BukuController extends Controller
{
    private const KUNCI_SESSION = 'buku';

    /**
     * Menampilkan daftar buku dari session, lengkap dengan pencarian,
     * filter kategori, pengurutan, dan ringkasan statistik.
     */
    public function index(Request $request): View
    {
        $semuaBuku = $this->ambilSemuaBuku();

        $kataKunci = trim((string) $request->query('q', ''));
        $kategoriDipilih = (string) $request->query('kategori', '');
        $urutan = in_array($request->query('urut'), ['kode', 'judul', 'jumlah'], true)
            ? $request->query('urut')
            : 'kode';

        $daftarBuku = $semuaBuku
            ->when($kataKunci !== '', fn (Collection $koleksi) => $koleksi->filter(
                fn (array $buku) => str_contains(
                    mb_strtolower($buku['kode'].' '.$buku['judul'].' '.$buku['penulis']),
                    mb_strtolower($kataKunci)
                )
            ))
            ->when($kategoriDipilih !== '', fn (Collection $koleksi) => $koleksi->where('kategori', $kategoriDipilih))
            ->sortBy($urutan, SORT_NATURAL | SORT_FLAG_CASE, $urutan === 'jumlah')
            ->values();

        return view('buku.index', [
            'daftarBuku' => $daftarBuku,
            'daftarKategori' => KatalogBuku::daftarKategori(),
            'kataKunci' => $kataKunci,
            'kategoriDipilih' => $kategoriDipilih,
            'urutan' => $urutan,
            'totalJudul' => $semuaBuku->count(),
            'totalEksemplar' => $semuaBuku->sum('jumlah'),
            'totalPerluPerbaikan' => $semuaBuku->where('kondisi', '!=', KondisiBuku::Baik->value)->count(),
        ]);
    }

    /**
     * Menampilkan form tambah buku.
     */
    public function create(): View
    {
        return view('buku.create', $this->dataPilihanForm());
    }

    /**
     * Menyimpan buku baru ke session lalu kembali ke halaman daftar.
     */
    public function store(SimpanBukuRequest $request): RedirectResponse
    {
        $dataBuku = $this->bentukDataBuku($request->validated());
        $dataBuku['kode'] = strtoupper($request->validated('kode'));

        $semuaBuku = $this->ambilSemuaBuku();
        $semuaBuku->put($dataBuku['kode'], $dataBuku);
        $this->simpanSemuaBuku($semuaBuku);

        return redirect()
            ->route('buku.index')
            ->with('sukses', "Buku \"{$dataBuku['judul']}\" berhasil ditambahkan.");
    }

    /**
     * Menampilkan detail satu buku berdasarkan kode.
     */
    public function show(string $kode): View|RedirectResponse
    {
        $buku = $this->cariBuku($kode);

        if ($buku === null) {
            return $this->bukuTidakDitemukan($kode);
        }

        return view('buku.show', ['buku' => $buku]);
    }

    /**
     * Menampilkan form edit berisi nilai lama buku yang dipilih.
     */
    public function edit(string $kode): View|RedirectResponse
    {
        $buku = $this->cariBuku($kode);

        if ($buku === null) {
            return $this->bukuTidakDitemukan($kode);
        }

        return view('buku.edit', ['buku' => $buku] + $this->dataPilihanForm());
    }

    /**
     * Memperbarui data buku di session berdasarkan kode.
     */
    public function update(SimpanBukuRequest $request, string $kode): RedirectResponse
    {
        $bukuLama = $this->cariBuku($kode);

        if ($bukuLama === null) {
            return $this->bukuTidakDitemukan($kode);
        }

        $bukuBaru = $this->bentukDataBuku($request->validated());
        $bukuBaru['kode'] = $bukuLama['kode'];

        $semuaBuku = $this->ambilSemuaBuku();
        $semuaBuku->put($bukuLama['kode'], $bukuBaru);
        $this->simpanSemuaBuku($semuaBuku);

        $namaBuku = $bukuLama['judul'] === $bukuBaru['judul']
            ? "\"{$bukuBaru['judul']}\""
            : "\"{$bukuLama['judul']}\" -> \"{$bukuBaru['judul']}\"";

        return redirect()
            ->route('buku.index')
            ->with('sukses', "Buku {$namaBuku} berhasil diperbarui.");
    }

    /**
     * Menghapus buku dari session berdasarkan kode.
     */
    public function destroy(string $kode): RedirectResponse
    {
        $buku = $this->cariBuku($kode);

        if ($buku === null) {
            return $this->bukuTidakDitemukan($kode);
        }

        $semuaBuku = $this->ambilSemuaBuku();
        $semuaBuku->forget($buku['kode']);
        $this->simpanSemuaBuku($semuaBuku);

        return redirect()
            ->route('buku.index')
            ->with('sukses', "Buku \"{$buku['judul']}\" berhasil dihapus.");
    }

    /**
     * Mengembalikan seluruh data ke contoh awal (fitur tambahan).
     */
    public function reset(): RedirectResponse
    {
        session()->put(self::KUNCI_SESSION, KatalogBuku::dataAwal());

        return redirect()
            ->route('buku.index')
            ->with('info', 'Data dikembalikan ke contoh awal.');
    }

    /**
     * Mengambil semua buku dari session. Jika session belum pernah diisi,
     * data awal dimuat agar daftar tidak kosong saat pertama dibuka.
     *
     * @return Collection<string, array<string, mixed>>
     */
    private function ambilSemuaBuku(): Collection
    {
        if (! session()->has(self::KUNCI_SESSION)) {
            session()->put(self::KUNCI_SESSION, KatalogBuku::dataAwal());
        }

        return collect(session(self::KUNCI_SESSION));
    }

    /**
     * @param  Collection<string, array<string, mixed>>  $semuaBuku
     */
    private function simpanSemuaBuku(Collection $semuaBuku): void
    {
        session()->put(self::KUNCI_SESSION, $semuaBuku->all());
    }

    /**
     * @return array<string, mixed>|null
     */
    private function cariBuku(string $kode): ?array
    {
        return $this->ambilSemuaBuku()->get(strtoupper($kode));
    }

    /**
     * Merapikan hasil validasi menjadi struktur data buku.
     *
     * @param  array<string, mixed>  $hasilValidasi
     * @return array<string, mixed>
     */
    private function bentukDataBuku(array $hasilValidasi): array
    {
        return [
            'judul' => $hasilValidasi['judul'],
            'penulis' => $hasilValidasi['penulis'],
            'penerbit' => $hasilValidasi['penerbit'] ?? null,
            'tahun' => (int) $hasilValidasi['tahun'],
            'kategori' => $hasilValidasi['kategori'],
            'lokasi' => $hasilValidasi['lokasi'],
            'jumlah' => (int) $hasilValidasi['jumlah'],
            'kondisi' => $hasilValidasi['kondisi'],
            'deskripsi' => $hasilValidasi['deskripsi'] ?? null,
        ];
    }

    /**
     * @return array{daftarKategori: list<string>, daftarKondisi: list<KondisiBuku>}
     */
    private function dataPilihanForm(): array
    {
        return [
            'daftarKategori' => KatalogBuku::daftarKategori(),
            'daftarKondisi' => KondisiBuku::cases(),
        ];
    }

    private function bukuTidakDitemukan(string $kode): RedirectResponse
    {
        return redirect()
            ->route('buku.index')
            ->with('info', 'Buku dengan kode "'.e($kode).'" tidak ditemukan.');
    }
}
