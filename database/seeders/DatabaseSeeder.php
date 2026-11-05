<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

/**
 * Testovací data: několik zařízení a jejich servisní historie.
 * Spuštění: php artisan migrate --seed  (nebo php artisan db:seed)
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pole testovacích zařízení – každé má i seznam servisních zásahů.
        $items = [
            [
                'name' => 'Vrtačka Makita HP457D',
                'inventory_number' => 'NAR-0001',
                'status' => 'active',
                'purchased_at' => '2022-03-15',
                'services' => [
                    ['description' => 'Výměna uhlíků', 'serviced_at' => '2023-05-10', 'cost' => 450.00],
                    ['description' => 'Výměna akumulátoru', 'serviced_at' => '2024-09-02', 'cost' => 1890.50],
                ],
            ],
            [
                'name' => 'Úhlová bruska Bosch GWS 7-125',
                'inventory_number' => 'NAR-0002',
                'status' => 'service',
                'purchased_at' => '2021-11-01',
                'services' => [
                    ['description' => 'Oprava vypínače', 'serviced_at' => '2026-10-20', 'cost' => 620.00],
                ],
            ],
            [
                'name' => 'Kompresor Güde 50 l',
                'inventory_number' => 'NAR-0003',
                'status' => 'retired',
                'purchased_at' => '2015-06-30',
                'services' => [
                    ['description' => 'Výměna těsnění', 'serviced_at' => '2018-02-14', 'cost' => 300.00],
                    ['description' => 'Výměna motoru', 'serviced_at' => '2020-07-08', 'cost' => 3200.00],
                    ['description' => 'Diagnostika – neopravitelné', 'serviced_at' => '2023-01-12', 'cost' => 0.00],
                ],
            ],
            [
                'name' => 'Laserový dálkoměr Leica Disto D2',
                'inventory_number' => 'NAR-0004',
                'status' => 'active',
                'purchased_at' => '2024-02-05',
                'services' => [],
            ],
        ];

        // Uložení do databáze.
        foreach ($items as $item) {
            // Oddělíme servisní záznamy od údajů o zařízení.
            $services = $item['services'];
            unset($item['services']);

            // Vytvoříme zařízení…
            $equipment = Equipment::create($item);

            // …a přes vztah k němu přidáme servisní záznamy (equipment_id se doplní samo).
            foreach ($services as $service) {
                $equipment->serviceRecords()->create($service);
            }
        }
    }
}
