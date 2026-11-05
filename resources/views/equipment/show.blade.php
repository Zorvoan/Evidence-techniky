@extends('layout')

@section('content')
    <h1>{{ $equipment->name }}</h1>

    <p>Evidenční číslo: {{ $equipment->inventory_number }}</p>
    <p>Stav: @include('equipment._status', ['status' => $equipment->status])</p>
    <p>Datum nákupu: {{ $equipment->purchased_at->format('d.m.Y') }}</p>
    <p><b>Celkem za servis: {{ number_format($totalCost, 2, ',', ' ') }} Kč</b></p>

    <p>
        <a href="{{ route('equipment.edit', $equipment) }}">Upravit</a> |
        <a href="{{ route('equipment.export', $equipment) }}">Export do CSV</a> |
        <a href="{{ route('equipment.index') }}">Zpět na seznam</a>
    </p>

    <form method="POST" action="{{ route('equipment.destroy', $equipment) }}" onsubmit="return confirm('Opravdu smazat?')">
        @csrf
        @method('DELETE')
        <button type="submit">Smazat zařízení</button>
    </form>

    <h2>Servisní historie</h2>

    <table>
        <tr>
            <th>Datum</th>
            <th>Popis</th>
            <th>Cena</th>
            <th></th>
        </tr>
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->serviced_at->format('d.m.Y') }}</td>
                <td>{{ $record->description }}</td>
                <td>{{ number_format($record->cost, 2, ',', ' ') }} Kč</td>
                <td>
                    <form method="POST" action="{{ route('service-records.destroy', $record) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Smazat</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    @if ($records->isEmpty())
        <p>Zatím žádný servis.</p>
    @endif

    {{-- pridani noveho servisu --}}
    <h2>Přidat servis</h2>

    <form method="POST" action="{{ route('service-records.store', $equipment) }}">
        @csrf

        <label>Popis</label>
        <textarea name="description">{{ old('description') }}</textarea>
        @error('description') <div class="error">{{ $message }}</div> @enderror

        <label>Datum servisu</label>
        <input type="date" name="serviced_at" value="{{ old('serviced_at', date('Y-m-d')) }}">
        @error('serviced_at') <div class="error">{{ $message }}</div> @enderror

        <label>Cena (Kč)</label>
        <input type="number" name="cost" step="0.01" min="0" value="{{ old('cost') }}">
        @error('cost') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Přidat</button>
    </form>
@endsection
