<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Resource controller pro zařízení (CRUD) + export servisní historie do CSV.
 */
class EquipmentController extends Controller
{
    /**
     * Seznam zařízení s možností filtrovat podle stavu (?status=active|service|retired).
     */
    public function index(Request $request): View
    {
        // Stav z URL – použijeme ho jen pokud je v povoleném seznamu.
        $status = $request->query('status');
        if (! is_string($status) || ! array_key_exists($status, Equipment::STATUSES)) {
            $status = null;
        }

        // Načteme zařízení; withSum přidá ke každému součet servisních nákladů (sloupec service_records_sum_cost).
        $equipment = Equipment::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->withSum('serviceRecords', 'cost')
            ->orderBy('inventory_number')
            ->get();

        return view('equipment.index', [
            'equipment' => $equipment,
            'status'    => $status,
        ]);
    }

    /**
     * Formulář pro nové zařízení.
     */
    public function create(): View
    {
        // Předáme prázdný model, aby formulář šel použít pro create i edit.
        return view('equipment.create', ['equipment' => new Equipment(['status' => 'active'])]);
    }

    /**
     * Uložení nového zařízení.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateEquipment($request);

        $equipment = Equipment::create($data);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Zařízení bylo přidáno.');
    }

    /**
     * Detail zařízení se servisní historií a součtem nákladů.
     */
    public function show(Equipment $equipment): View
    {
        return view('equipment.show', [
            'equipment' => $equipment,
            'records'   => $equipment->serviceRecords,      // servisní historie (seřazená v modelu)
            'totalCost' => $equipment->totalServiceCost(),  // součet nákladů
        ]);
    }

    /**
     * Formulář pro úpravu zařízení.
     */
    public function edit(Equipment $equipment): View
    {
        return view('equipment.edit', ['equipment' => $equipment]);
    }

    /**
     * Uložení změn zařízení.
     */
    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        // Při úpravě předáme zařízení, aby unikátnost ignorovala jeho vlastní evidenční číslo.
        $data = $this->validateEquipment($request, $equipment);

        $equipment->update($data);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Zařízení bylo upraveno.');
    }

    /**
     * Smazání zařízení (servisní záznamy se smažou díky cascadeOnDelete v migraci).
     */
    public function destroy(Equipment $equipment): RedirectResponse
    {
        $equipment->delete();

        return redirect()
            ->route('equipment.index')
            ->with('success', 'Zařízení bylo smazáno.');
    }

    /**
     * Volitelné rozšíření: export servisní historie jednoho zařízení do CSV.
     */
    public function export(Equipment $equipment): StreamedResponse
    {
        // Název souboru, např. servis-NAR-0001.csv
        $fileName = 'servis-' . $equipment->inventory_number . '.csv';

        return response()->streamDownload(function () use ($equipment) {
            $out = fopen('php://output', 'w');

            // BOM – aby Excel správně zobrazil českou diakritiku (UTF-8).
            fwrite($out, "\xEF\xBB\xBF");

            // Hlavička CSV (oddělovač ";" kvůli českému Excelu).
            fputcsv($out, ['Datum servisu', 'Popis', 'Cena (Kč)'], ';');

            // Jednotlivé servisní záznamy.
            foreach ($equipment->serviceRecords as $record) {
                fputcsv($out, [
                    $record->serviced_at->format('Y-m-d'),
                    $record->description,
                    $record->cost,
                ], ';');
            }

            // Poslední řádek se součtem nákladů.
            fputcsv($out, ['', 'Celkem', number_format($equipment->totalServiceCost(), 2, '.', '')], ';');

            fclose($out);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Společná validace pro store i update.
     *
     * @param  Equipment|null  $equipment  upravované zařízení (null = nové)
     */
    private function validateEquipment(Request $request, ?Equipment $equipment = null): array
    {
        return $request->validate([
            // Název je povinný text.
            'name' => ['required', 'string', 'max:255'],

            // Evidenční číslo je povinné a jedinečné (při úpravě ignorujeme aktuální záznam).
            'inventory_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('equipment', 'inventory_number')->ignore($equipment?->id),
            ],

            // Stav musí být jeden z povolených (active, service, retired).
            'status' => ['required', Rule::in(array_keys(Equipment::STATUSES))],

            // Datum nákupu: přesný formát RRRR-MM-DD, rozumný rozsah (ne v budoucnosti, ne před r. 1900).
            'purchased_at' => ['required', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
        ], [
            // České chybové hlášky.
            'name.required'                => 'Zadejte název zařízení.',
            'inventory_number.required'    => 'Zadejte evidenční číslo.',
            'inventory_number.unique'      => 'Toto evidenční číslo už existuje.',
            'status.required'              => 'Vyberte stav.',
            'status.in'                    => 'Neplatný stav zařízení.',
            'purchased_at.required'        => 'Zadejte datum nákupu.',
            'purchased_at.date_format'     => 'Datum nákupu musí být ve formátu RRRR-MM-DD.',
            'purchased_at.after_or_equal'  => 'Datum nákupu je příliš staré.',
            'purchased_at.before_or_equal' => 'Datum nákupu nesmí být v budoucnosti.',
        ]);
    }
}
