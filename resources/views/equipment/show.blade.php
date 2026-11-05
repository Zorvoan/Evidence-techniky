@extends('layout')

@section('content')
    <p><a href="{{ route('equipment.index') }}">&larr; Zpět na seznam</a></p>

    <div class="page-head">
        <h1>{{ $equipment->name }}</h1>

        <div class="actions">
            <a href="{{ route('equipment.export', $equipment) }}" class="btn">Export CSV</a>
            <a href="{{ route('equipment.edit', $equipment) }}" class="btn">Upravit</a>
            <form method="POST" action="{{ route('equipment.destroy', $equipment) }}" onsubmit="return confirm('Opravdu smazat?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Smazat</button>
            </form>
        </div>
    </div>

    {{-- zakladni udaje --}}
    <div class="stats">
        <div class="stat"><span class="stat-label">Evidenční číslo</span><strong class="code">{{ $equipment->inventory_number }}</strong></div>
        <div class="stat"><span class="stat-label">Stav</span>@include('equipment._status', ['status' => $equipment->status])</div>
        <div class="stat"><span class="stat-label">Datum nákupu</span><strong>{{ $equipment->purchased_at->format('d.m.Y') }}</strong></div>
        <div class="stat"><span class="stat-label">Celkem za servis</span><strong>{{ number_format($totalCost, 2, ',', ' ') }} Kč</strong></div>
    </div>

    <div class="two-cols">
        <div class="card table-wrap">
            <h2>Servisní historie</h2>

            <table>
                <tr>
                    <th>Datum</th>
                    <th>Popis</th>
                    <th class="right">Cena</th>
                    <th></th>
                </tr>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $record->serviced_at->format('d.m.Y') }}</td>
                        <td>{{ $record->description }}</td>
                        <td class="right">{{ number_format($record->cost, 2, ',', ' ') }} Kč</td>
                        <td class="right">
                            <form method="POST" action="{{ route('service-records.destroy', $record) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger">Smazat</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Zatím žádný servis.</td></tr>
                @endforelse
            </table>
        </div>

        {{-- pridani noveho servisu --}}
        <div class="card">
            <h2>Přidat servis</h2>

            <form method="POST" action="{{ route('service-records.store', $equipment) }}">
                @csrf

                <label>Popis</label>
                <textarea name="description" rows="3">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror

                <label>Datum servisu</label>
                <input type="date" name="serviced_at" value="{{ old('serviced_at', date('Y-m-d')) }}">
                @error('serviced_at') <div class="error">{{ $message }}</div> @enderror

                <label>Cena (Kč)</label>
                <input type="number" name="cost" step="0.01" min="0" value="{{ old('cost') }}" placeholder="0,00">
                @error('cost') <div class="error">{{ $message }}</div> @enderror

                <button type="submit" class="btn btn-primary">Přidat</button>
            </form>
        </div>
    </div>
@endsection
