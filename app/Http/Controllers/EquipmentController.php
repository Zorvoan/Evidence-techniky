<?php

// namespace = slozka, ve ktere trida je (app/Http/Controllers)
namespace App\Http\Controllers;

use App\Models\Equipment;          // model zarizeni
use Illuminate\Http\Request;       // data z formulare / URL
use Illuminate\Validation\Rule;    // pravidla pro validaci (unique, in)

// resource controller pro zarizeni (CRUD) + export do CSV
class EquipmentController extends Controller
{
    // seznam zarizeni + filtr podle stavu
    // GET /equipment nebo /equipment?status=active
    public function index(Request $request)
    {
        // stav z URL (?status=...)
        $status = $request->status;

        // zaklad dotazu: ke kazdemu zarizeni rovnou soucet nakladu (service_records_sum_cost)
        // a serazeni podle evidencniho cisla
        $query = Equipment::withSum('serviceRecords', 'cost')->orderBy('inventory_number');

        // filtruju jen kdyz je stav v povolenem seznamu
        if (in_array($status, array_keys(Equipment::STATUSES), true)) {
            $query->where('status', $status);
        } else {
            $status = null; // neplatny nebo zadny stav = zobrazit vse
        }

        // provedeni dotazu
        $equipment = $query->get();

        // zobrazeni sablony resources/views/equipment/index.blade.php
        return view('equipment.index', compact('equipment', 'status'));
    }

    // formular pro nove zarizeni
    // GET /equipment/create
    public function create()
    {
        // prazdne zarizeni, aby formular sel pouzit pro pridani i upravu
        $equipment = new Equipment(['status' => 'active']);

        // zobrazeni sablony resources/views/equipment/create.blade.php
        return view('equipment.create', compact('equipment'));
    }

    // ulozeni noveho zarizeni
    // POST /equipment
    public function store(Request $request)
    {
        // kontrola dat, pri chybe se vrati na formular
        $data = $request->validate($this->rules());

        // ulozeni do DB
        $equipment = Equipment::create($data);

        // presmerovani na detail noveho zarizeni s hlaskou
        return redirect()->route('equipment.show', $equipment)->with('success', 'Zařízení bylo přidáno.');
    }

    // detail zarizeni se servisni historii
    // GET /equipment/{equipment}
    public function show(Equipment $equipment)
    {
        // servisni zaznamy (serazene ve vztahu v modelu)
        $records = $equipment->serviceRecords;

        // soucet nakladu za servis
        $totalCost = $equipment->totalServiceCost();

        // zobrazeni sablony resources/views/equipment/show.blade.php
        return view('equipment.show', compact('equipment', 'records', 'totalCost'));
    }

    // formular pro upravu
    // GET /equipment/{equipment}/edit
    public function edit(Equipment $equipment)
    {
        // zobrazeni sablony resources/views/equipment/edit.blade.php
        return view('equipment.edit', compact('equipment'));
    }

    // ulozeni zmen
    // PUT /equipment/{equipment}
    public function update(Request $request, Equipment $equipment)
    {
        // posilam id, aby unique nehlasilo chybu na vlastni evidencni cislo
        $data = $request->validate($this->rules($equipment->id));

        // ulozeni zmen do DB
        $equipment->update($data);

        // zpet na detail s hlaskou
        return redirect()->route('equipment.show', $equipment)->with('success', 'Zařízení bylo upraveno.');
    }

    // smazani zarizeni
    // DELETE /equipment/{equipment}
    // servisni zaznamy se smazou samy (onDelete cascade v migraci)
    public function destroy(Equipment $equipment)
    {
        // smazani z DB
        $equipment->delete();

        // zpet na seznam s hlaskou
        return redirect()->route('equipment.index')->with('success', 'Zařízení bylo smazáno.');
    }

    // export servisni historie do csv
    // GET /equipment/{equipment}/export
    public function export(Equipment $equipment)
    {
        // servisni zaznamy tohoto zarizeni
        $records = $equipment->serviceRecords;

        // streamDownload posle soubor rovnou ke stazeni
        return response()->streamDownload(function () use ($records, $equipment) {
            // zapisuju primo do odpovedi
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // kvuli diakritice v Excelu

            // hlavicka, oddelovac je strednik (cesky Excel)
            fputcsv($file, ['Datum', 'Popis', 'Cena'], ';');

            // jeden radek za kazdy servis
            foreach ($records as $record) {
                fputcsv($file, [$record->serviced_at->format('d.m.Y'), $record->description, $record->cost], ';');
            }

            // posledni radek se souctem
            fputcsv($file, ['', 'Celkem', $equipment->totalServiceCost()], ';');

            fclose($file);
        }, 'servis-' . $equipment->inventory_number . '.csv'); // nazev souboru
    }

    // validacni pravidla pro pridani i upravu
    // $id = id upravovaneho zarizeni (pri pridani je null)
    private function rules($id = null)
    {
        return [
            // povinny text, max 255 znaku
            'name' => 'required|string|max:255',
            // evidencni cislo musi byt jedinecne, pri uprave se ignoruje vlastni zaznam
            'inventory_number' => ['required', 'string', 'max:50', Rule::unique('equipment')->ignore($id)],
            // stav jen z povoleneho seznamu (active, service, retired)
            'status' => ['required', Rule::in(array_keys(Equipment::STATUSES))],
            // datum ve formatu RRRR-MM-DD, po roce 1900 a ne v budoucnosti
            'purchased_at' => 'required|date_format:Y-m-d|after:1900-01-01|before_or_equal:today',
        ];
    }
}
