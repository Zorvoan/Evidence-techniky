<?php

namespace Tests\Feature;

use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Základní testy evidence nářadí. Spuštění: php artisan test
 */
class EquipmentTest extends TestCase
{
    // Před každým testem se databáze vyčistí a znovu zmigruje.
    use RefreshDatabase;

    /** Pomocná metoda – vytvoří jedno zařízení. */
    private function makeEquipment(array $data = []): Equipment
    {
        return Equipment::create(array_merge([
            'name' => 'Vrtačka',
            'inventory_number' => 'NAR-1',
            'status' => 'active',
            'purchased_at' => '2024-01-01',
        ], $data));
    }

    public function test_zarizeni_lze_vytvorit(): void
    {
        $this->post('/equipment', [
            'name' => 'Bruska',
            'inventory_number' => 'NAR-99',
            'status' => 'service',
            'purchased_at' => '2023-05-05',
        ])->assertRedirect();

        $this->assertDatabaseHas('equipment', ['inventory_number' => 'NAR-99', 'status' => 'service']);
    }

    public function test_evidencni_cislo_musi_byt_jedinecne(): void
    {
        $this->makeEquipment();

        $this->post('/equipment', [
            'name' => 'Jiná vrtačka',
            'inventory_number' => 'NAR-1', // už existuje
            'status' => 'active',
            'purchased_at' => '2024-01-01',
        ])->assertSessionHasErrors('inventory_number');
    }

    public function test_uprava_muze_ponechat_vlastni_evidencni_cislo(): void
    {
        $equipment = $this->makeEquipment();

        $this->put("/equipment/{$equipment->id}", [
            'name' => 'Přejmenovaná vrtačka',
            'inventory_number' => 'NAR-1', // stejné číslo jako má samo – musí projít
            'status' => 'retired',
            'purchased_at' => '2024-01-01',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('equipment', ['id' => $equipment->id, 'status' => 'retired']);
    }

    public function test_neplatny_stav_a_datum_neprojdou(): void
    {
        $this->post('/equipment', [
            'name' => 'Test',
            'inventory_number' => 'NAR-2',
            'status' => 'broken',          // není v povoleném seznamu
            'purchased_at' => '32.13.2024', // nesmyslné datum
        ])->assertSessionHasErrors(['status', 'purchased_at']);
    }

    public function test_filtr_podle_stavu(): void
    {
        $this->makeEquipment(['name' => 'Aktivni kus', 'inventory_number' => 'A-1', 'status' => 'active']);
        $this->makeEquipment(['name' => 'Vyrazeny kus', 'inventory_number' => 'R-1', 'status' => 'retired']);

        $this->get('/equipment?status=retired')
            ->assertOk()
            ->assertSee('Vyrazeny kus')
            ->assertDontSee('Aktivni kus');
    }

    public function test_servisni_zaznam_a_soucet_nakladu(): void
    {
        $equipment = $this->makeEquipment();

        // Dva servisní zásahy přidané z detailu zařízení.
        $this->post("/equipment/{$equipment->id}/service-records", [
            'description' => 'Výměna uhlíků', 'serviced_at' => '2025-01-10', 'cost' => '100.50',
        ])->assertSessionHasNoErrors();
        $this->post("/equipment/{$equipment->id}/service-records", [
            'description' => 'Čištění', 'serviced_at' => '2025-02-10', 'cost' => '49.50',
        ])->assertSessionHasNoErrors();

        $this->assertEquals(150.00, $equipment->totalServiceCost());
        $this->get("/equipment/{$equipment->id}")->assertSee('150,00 Kč');
    }

    public function test_zaporna_cena_neprojde(): void
    {
        $equipment = $this->makeEquipment();

        $this->post("/equipment/{$equipment->id}/service-records", [
            'description' => 'Oprava', 'serviced_at' => '2025-01-10', 'cost' => '-5',
        ])->assertSessionHasErrors('cost');
    }

    public function test_export_csv(): void
    {
        $equipment = $this->makeEquipment();
        $equipment->serviceRecords()->create(['description' => 'Oprava', 'serviced_at' => '2025-01-10', 'cost' => 200]);

        $response = $this->get("/equipment/{$equipment->id}/export");

        $response->assertOk();
        $this->assertStringContainsString('Oprava', $response->streamedContent());
    }
}
