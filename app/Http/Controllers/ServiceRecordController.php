<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\ServiceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller pro servisní záznamy – přidávají se a mažou z detailu zařízení.
 */
class ServiceRecordController extends Controller
{
    /**
     * Uložení nového servisního záznamu k danému zařízení.
     */
    public function store(Request $request, Equipment $equipment): RedirectResponse
    {
        $data = $request->validate([
            // Popis zásahu je povinný.
            'description' => ['required', 'string', 'max:1000'],

            // Datum servisu: formát RRRR-MM-DD, rozumný rozsah.
            'serviced_at' => ['required', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before_or_equal:today'],

            // Cena: nezáporné desetinné číslo s max. 2 desetinnými místy.
            'cost' => ['required', 'numeric', 'min:0', 'decimal:0,2', 'max:99999999.99'],
        ], [
            // České chybové hlášky.
            'description.required'        => 'Zadejte popis servisu.',
            'serviced_at.required'        => 'Zadejte datum servisu.',
            'serviced_at.date_format'     => 'Datum servisu musí být ve formátu RRRR-MM-DD.',
            'serviced_at.after_or_equal'  => 'Datum servisu je příliš staré.',
            'serviced_at.before_or_equal' => 'Datum servisu nesmí být v budoucnosti.',
            'cost.required'               => 'Zadejte cenu.',
            'cost.numeric'                => 'Cena musí být číslo.',
            'cost.min'                    => 'Cena nesmí být záporná.',
            'cost.decimal'                => 'Cena může mít nejvýše 2 desetinná místa.',
            'cost.max'                    => 'Cena je příliš vysoká.',
        ]);

        // Vytvoření přes vztah – equipment_id se doplní automaticky.
        $equipment->serviceRecords()->create($data);

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Servisní záznam byl přidán.');
    }

    /**
     * Smazání servisního záznamu.
     */
    public function destroy(ServiceRecord $serviceRecord): RedirectResponse
    {
        // Zapamatujeme si zařízení, abychom se na něj mohli vrátit.
        $equipment = $serviceRecord->equipment;

        $serviceRecord->delete();

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('success', 'Servisní záznam byl smazán.');
    }
}
