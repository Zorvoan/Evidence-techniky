{{-- spolecny formular pro pridani a upravu --}}
<label>Název</label>
<input type="text" name="name" value="{{ old('name', $equipment->name) }}">
@error('name') <div class="error">{{ $message }}</div> @enderror

<label>Evidenční číslo</label>
<input type="text" name="inventory_number" value="{{ old('inventory_number', $equipment->inventory_number) }}">
@error('inventory_number') <div class="error">{{ $message }}</div> @enderror

<label>Stav</label>
<select name="status">
    @foreach (App\Models\Equipment::STATUSES as $key => $name)
        <option value="{{ $key }}" {{ old('status', $equipment->status) == $key ? 'selected' : '' }}>{{ $name }}</option>
    @endforeach
</select>
@error('status') <div class="error">{{ $message }}</div> @enderror

<label>Datum nákupu</label>
<input type="date" name="purchased_at" value="{{ old('purchased_at', $equipment->purchased_at?->format('Y-m-d')) }}">
@error('purchased_at') <div class="error">{{ $message }}</div> @enderror
