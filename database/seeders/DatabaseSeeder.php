<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // testovaci data, spusti se pres php artisan migrate --seed
    public function run(): void
    {
        $vrtacka = Equipment::create([
            'name' => 'Vrtačka Makita HP457D',
            'inventory_number' => 'NAR-0001',
            'status' => 'active',
            'purchased_at' => '2022-03-15',
        ]);
        $vrtacka->serviceRecords()->create(['description' => 'Výměna uhlíků', 'serviced_at' => '2023-05-10', 'cost' => 450]);
        $vrtacka->serviceRecords()->create(['description' => 'Nová baterie', 'serviced_at' => '2024-09-02', 'cost' => 1890.50]);

        $bruska = Equipment::create([
            'name' => 'Úhlová bruska Bosch',
            'inventory_number' => 'NAR-0002',
            'status' => 'service',
            'purchased_at' => '2021-11-01',
        ]);
        $bruska->serviceRecords()->create(['description' => 'Oprava vypínače', 'serviced_at' => '2026-10-20', 'cost' => 620]);

        $kompresor = Equipment::create([
            'name' => 'Kompresor 50 l',
            'inventory_number' => 'NAR-0003',
            'status' => 'retired',
            'purchased_at' => '2015-06-30',
        ]);
        $kompresor->serviceRecords()->create(['description' => 'Výměna těsnění', 'serviced_at' => '2018-02-14', 'cost' => 300]);
        $kompresor->serviceRecords()->create(['description' => 'Výměna motoru', 'serviced_at' => '2020-07-08', 'cost' => 3200]);

        // tohle zarizeni zatim nema zadny servis
        Equipment::create([
            'name' => 'Laserový dálkoměr',
            'inventory_number' => 'NAR-0004',
            'status' => 'active',
            'purchased_at' => '2024-02-05',
        ]);
    }
}
