<?php

namespace Tests\Feature;

use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    private function createEquipment($data = [])
    {
        return Equipment::create(array_merge([
            'name' => 'Vrtačka',
            'inventory_number' => 'NAR-1',
            'status' => 'active',
            'purchased_at' => '2024-01-01',
        ], $data));
    }

    public function test_create_equipment()
    {
        $this->post('/equipment', [
            'name' => 'Bruska',
            'inventory_number' => 'NAR-99',
            'status' => 'service',
            'purchased_at' => '2023-05-05',
        ]);

        $this->assertDatabaseHas('equipment', ['inventory_number' => 'NAR-99']);
    }

    public function test_inventory_number_must_be_unique()
    {
        $this->createEquipment();

        $response = $this->post('/equipment', [
            'name' => 'Jiná vrtačka',
            'inventory_number' => 'NAR-1',
            'status' => 'active',
            'purchased_at' => '2024-01-01',
        ]);

        $response->assertSessionHasErrors('inventory_number');
    }

    public function test_update_with_same_inventory_number()
    {
        $equipment = $this->createEquipment();

        $response = $this->put('/equipment/' . $equipment->id, [
            'name' => 'Vrtačka 2',
            'inventory_number' => 'NAR-1',
            'status' => 'retired',
            'purchased_at' => '2024-01-01',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_wrong_status_and_date()
    {
        $response = $this->post('/equipment', [
            'name' => 'Test',
            'inventory_number' => 'NAR-2',
            'status' => 'broken',
            'purchased_at' => '32.13.2024',
        ]);

        $response->assertSessionHasErrors(['status', 'purchased_at']);
    }

    public function test_filter()
    {
        $this->createEquipment(['name' => 'Aktivni kus', 'inventory_number' => 'A-1']);
        $this->createEquipment(['name' => 'Vyrazeny kus', 'inventory_number' => 'R-1', 'status' => 'retired']);

        $this->get('/equipment?status=retired')
            ->assertSee('Vyrazeny kus')
            ->assertDontSee('Aktivni kus');
    }

    public function test_service_cost_sum()
    {
        $equipment = $this->createEquipment();

        $this->post('/equipment/' . $equipment->id . '/service-records', [
            'description' => 'Oprava', 'serviced_at' => '2025-01-10', 'cost' => '100.50',
        ]);
        $this->post('/equipment/' . $equipment->id . '/service-records', [
            'description' => 'Čištění', 'serviced_at' => '2025-02-10', 'cost' => '49.50',
        ]);

        $this->assertEquals(150, $equipment->totalServiceCost());
    }

    public function test_negative_cost()
    {
        $equipment = $this->createEquipment();

        $response = $this->post('/equipment/' . $equipment->id . '/service-records', [
            'description' => 'Oprava', 'serviced_at' => '2025-01-10', 'cost' => '-5',
        ]);

        $response->assertSessionHasErrors('cost');
    }

    public function test_csv_export()
    {
        $equipment = $this->createEquipment();
        $equipment->serviceRecords()->create(['description' => 'Oprava', 'serviced_at' => '2025-01-10', 'cost' => 200]);

        $response = $this->get('/equipment/' . $equipment->id . '/export');

        $this->assertStringContainsString('Oprava', $response->streamedContent());
    }
}
