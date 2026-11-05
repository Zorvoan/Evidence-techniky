<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\ServiceRecord;
use Illuminate\Http\Request;

class ServiceRecordController extends Controller
{
    // pridani servisu z detailu zarizeni
    public function store(Request $request, Equipment $equipment)
    {
        $data = $request->validate([
            'description' => 'required|string|max:1000',
            'serviced_at' => 'required|date_format:Y-m-d|after:1900-01-01|before_or_equal:today',
            'cost' => 'required|numeric|min:0|decimal:0,2', // nezaporne cislo, max 2 desetinna mista
        ]);

        // equipment_id se doplni samo diky vztahu
        $equipment->serviceRecords()->create($data);

        return redirect()->route('equipment.show', $equipment)->with('success', 'Servis byl přidán.');
    }

    public function destroy(ServiceRecord $serviceRecord)
    {
        $equipment = $serviceRecord->equipment;
        $serviceRecord->delete();

        return redirect()->route('equipment.show', $equipment)->with('success', 'Servis byl smazán.');
    }
}
