{{--
    Společná pole formuláře pro vytvoření i úpravu zařízení.
    old('pole', výchozí) vrátí zadanou hodnotu po neúspěšné validaci, jinak hodnotu z modelu.
--}}

<label for="name">Název</label>
<input type="text" id="name" name="name" value="{{ old('name', $equipment->name) }}" required>
@error('name') <div class="error">{{ $message }}</div> @enderror

<label for="inventory_number">Evidenční číslo</label>
<input type="text" id="inventory_number" name="inventory_number"
       value="{{ old('inventory_number', $equipment->inventory_number) }}" required>
@error('inventory_number') <div class="error">{{ $message }}</div> @enderror

<label for="status">Stav</label>
<select id="status" name="status" required>
    {{-- Možnosti se berou z povoleného seznamu v modelu --}}
    @foreach (\App\Models\Equipment::STATUSES as $key => $label)
        <option value="{{ $key }}" @selected(old('status', $equipment->status) === $key)>{{ $label }}</option>
    @endforeach
</select>
@error('status') <div class="error">{{ $message }}</div> @enderror

<label for="purchased_at">Datum nákupu</label>
{{-- type="date" posílá datum ve formátu RRRR-MM-DD --}}
<input type="date" id="purchased_at" name="purchased_at"
       value="{{ old('purchased_at', $equipment->purchased_at?->format('Y-m-d')) }}" required>
@error('purchased_at') <div class="error">{{ $message }}</div> @enderror
