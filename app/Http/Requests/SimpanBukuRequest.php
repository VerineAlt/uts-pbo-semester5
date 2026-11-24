<?php

namespace App\Http\Requests;

use App\Enums\KondisiBuku;
use App\Support\KatalogBuku;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SimpanBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk tambah (POST) dan ubah (PUT/PATCH).
     * Kode hanya divalidasi saat tambah karena kode adalah kunci data.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $aturan = [
            'judul' => ['required', 'string', 'max:150'],
            'penulis' => ['required', 'string', 'max:100'],
            'penerbit' => ['nullable', 'string', 'max:100'],
            'tahun' => ['required', 'integer', 'between:1900,'.now()->year],
            'kategori' => ['required', Rule::in(KatalogBuku::daftarKategori())],
            'lokasi' => ['required', 'string', 'max:50'],
            'jumlah' => ['required', 'integer', 'min:0', 'max:9999'],
            'kondisi' => ['required', Rule::in(KondisiBuku::daftarNilai())],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ];

        if ($this->isMethod('POST')) {
            $aturan['kode'] = [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9\-]+$/',
                $this->kodeBelumDipakai(),
            ];
        }

        return $aturan;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'integer' => ':attribute harus berupa angka bulat.',
            'min' => ':attribute minimal :min.',
            'between' => ':attribute harus di antara :min dan :max.',
            'in' => ':attribute yang dipilih tidak valid.',
            'kode.regex' => 'Kode hanya boleh berisi huruf, angka, dan tanda hubung.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'kode' => 'Kode buku',
            'judul' => 'Judul buku',
            'penulis' => 'Penulis',
            'penerbit' => 'Penerbit',
            'tahun' => 'Tahun terbit',
            'kategori' => 'Kategori',
            'lokasi' => 'Lokasi rak',
            'jumlah' => 'Jumlah',
            'kondisi' => 'Kondisi',
            'deskripsi' => 'Deskripsi',
        ];
    }

    private function kodeBelumDipakai(): Closure
    {
        return function (string $atribut, mixed $nilai, Closure $gagal): void {
            $kodeTersimpan = array_keys(session('buku', KatalogBuku::dataAwal()));

            if (in_array(strtoupper((string) $nilai), array_map('strtoupper', $kodeTersimpan), true)) {
                $gagal('Kode buku sudah digunakan.');
            }
        };
    }
}
