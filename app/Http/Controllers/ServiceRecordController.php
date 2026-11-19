<?php

// namespace = slozka, ve ktere trida je (app/Http/Controllers)
namespace App\Http\Controllers;

use App\Models\Equipment;      // model zarizeni
use App\Models\ServiceRecord;  // model servisniho zaznamu
use Illuminate\Http\Request;   // data z formulare

// controller pro servisni zaznamy (pridani a smazani)
class ServiceRecordController extends Controller
{
    // pridani servisu z detailu zarizeni
    // POST /equipment/{equipment}/service-records
    // Laravel podle id v URL sam najde zarizeni a preda ho jako $equipment
    public function store(Request $request, Equipment $equipment)
    {
        // kontrola dat z formulare, kdyz neprojdou, vrati se zpet na formular s chybami
        $data = $request->validate([
            'description' => 'required|string|max:1000', // povinny text
            'serviced_at' => 'required|date_format:Y-m-d|after:1900-01-01|before_or_equal:today', // datum RRRR-MM-DD, ne v budoucnosti
            'cost' => 'required|numeric|min:0|decimal:0,2', // nezaporne cislo, max 2 desetinna mista
        ]);

        // equipment_id se doplni samo diky vztahu
        $equipment->serviceRecords()->create($data);

        // presmerovani zpet na detail zarizeni s hlaskou
        return redirect()->route('equipment.show', $equipment)->with('success', 'Servis byl přidán.');
    }

    // smazani servisniho zaznamu
    // DELETE /service-records/{serviceRecord}
    public function destroy(ServiceRecord $serviceRecord)
    {
        // zapamatuju si zarizeni, abych se na nej mohl vratit
        $equipment = $serviceRecord->equipment;

        // smazani z DB
        $serviceRecord->delete();

        // zpet na detail zarizeni s hlaskou
        return redirect()->route('equipment.show', $equipment)->with('success', 'Servis byl smazán.');
    }
}
