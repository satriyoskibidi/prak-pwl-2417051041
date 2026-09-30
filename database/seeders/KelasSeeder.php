<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['ILKOMP 24 A', 'ILKOMP 24 B'];
        foreach ($data as $namaKelas) {
            Kelas::firstOrCreate(['nama_kelas' => $namaKelas]);
        }
    }
}
