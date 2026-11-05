<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    // seznam zarizeni + filtr podle stavu
    public function index(Request $request)
    {
        $status = $request->status;

        $query = Equipment::withSum('serviceRecords', 'cost')->orderBy('inventory_number');

        // filtruju jen kdyz je stav v povolenem seznamu
        if (in_array($status, array_keys(Equipment::STATUSES), true)) {
            $query->where('status', $status);
        } else {
            $status = null;
        }

        $equipment = $query->get();

        return view('equipment.index', compact('equipment', 'status'));
    }

    public function create()
    {
        $equipment = new Equipment(['status' => 'active']);

        return view('equipment.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $equipment = Equipment::create($data);

        return redirect()->route('equipment.show', $equipment)->with('success', 'Zařízení bylo přidáno.');
    }

    // detail zarizeni se servisni historii
    public function show(Equipment $equipment)
    {
        $records = $equipment->serviceRecords;
        $totalCost = $equipment->totalServiceCost();

        return view('equipment.show', compact('equipment', 'records', 'totalCost'));
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        // posilam id, aby unique nehlasilo chybu na vlastni evidencni cislo
        $data = $request->validate($this->rules($equipment->id));

        $equipment->update($data);

        return redirect()->route('equipment.show', $equipment)->with('success', 'Zařízení bylo upraveno.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Zařízení bylo smazáno.');
    }

    // export servisni historie do csv
    public function export(Equipment $equipment)
    {
        $records = $equipment->serviceRecords;

        return response()->streamDownload(function () use ($records, $equipment) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // kvuli diakritice v Excelu

            fputcsv($file, ['Datum', 'Popis', 'Cena'], ';');
            foreach ($records as $record) {
                fputcsv($file, [$record->serviced_at->format('d.m.Y'), $record->description, $record->cost], ';');
            }
            fputcsv($file, ['', 'Celkem', $equipment->totalServiceCost()], ';');

            fclose($file);
        }, 'servis-' . $equipment->inventory_number . '.csv');
    }

    // validacni pravidla pro pridani i upravu
    private function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'inventory_number' => ['required', 'string', 'max:50', Rule::unique('equipment')->ignore($id)],
            'status' => ['required', Rule::in(array_keys(Equipment::STATUSES))],
            'purchased_at' => 'required|date_format:Y-m-d|after:1900-01-01|before_or_equal:today',
        ];
    }
}
