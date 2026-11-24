<?php

namespace App\Enums;

enum KondisiBuku: string
{
    case Baik = 'Baik';
    case RusakRingan = 'Rusak Ringan';
    case RusakBerat = 'Rusak Berat';

    /**
     * Nama kelas CSS untuk lencana kondisi pada tampilan.
     */
    public function kelasLencana(): string
    {
        return match ($this) {
            self::Baik => 'lencana-baik',
            self::RusakRingan => 'lencana-ringan',
            self::RusakBerat => 'lencana-berat',
        };
    }

    /**
     * @return list<string>
     */
    public static function daftarNilai(): array
    {
        return array_column(self::cases(), 'value');
    }
}
