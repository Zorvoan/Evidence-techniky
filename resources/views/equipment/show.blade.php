{{-- Detail zařízení: údaje, servisní historie, součet nákladů a formulář pro nový servis --}}
@extends('layout')

@section('title', $equipment->name)

@section('content')
    {{-- Základní údaje o zařízení --}}
    <div class="card">
        <h1 style="margin-top:0">{{ $equipment->name }}</h1>

        <table>
            <tr><th>Evidenční číslo</th><td>{{ $equipment->inventory_number }}</td></tr>
            <tr><th>Stav</th><td>@include('equipment._status', ['status' => $equipment->status])</td></tr>
            <tr><th>Datum nákupu</th><td>{{ $equipment->purchased_at->format('d.m.Y') }}</td></tr>
            {{-- Součet servisních nákladů pro toto zařízení --}}
            <tr><th>Servisní náklady celkem</th><td><strong>{{ number_format($totalCost, 2, ',', ' ') }} Kč</strong></td></tr>
        </table>

        {{-- div místo p – formulář uvnitř <p> by prohlížeč odsunul na nový řádek --}}
        <div style="margin-top:16px">
            <a href="{{ route('equipment.edit', $equipment) }}" class="btn">Upravit</a>
            <a href="{{ route('equipment.export', $equipment) }}" class="btn">Export CSV</a>

            {{-- Smazání zařízení – s potvrzením --}}
            <form method="POST" action="{{ route('equipment.destroy', $equipment) }}" class="inline"
                  onsubmit="return confirm('Opravdu smazat zařízení i s celou servisní historií?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Smazat</button>
            </form>

            <a href="{{ route('equipment.index') }}" class="btn">Zpět na seznam</a>
        </div>
    </div>

    {{-- Servisní historie --}}
    <div class="card">
        <h2 style="margin-top:0">Servisní historie</h2>

        <table>
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Popis</th>
                    <th class="right">Cena</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $record->serviced_at->format('d.m.Y') }}</td>
                        <td>{{ $record->description }}</td>
                        <td class="right">{{ number_format($record->cost, 2, ',', ' ') }} Kč</td>
                        <td class="right">
                            <form method="POST" action="{{ route('service-records.destroy', $record) }}" class="inline"
                                  onsubmit="return confirm('Smazat servisní záznam?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn">Smazat</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Zatím žádný servisní záznam.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Celkem</th>
                    <th class="right">{{ number_format($totalCost, 2, ',', ' ') }} Kč</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Formulář pro přidání servisního záznamu --}}
    <div class="card">
        <h2 style="margin-top:0">Přidat servisní záznam</h2>

        <form method="POST" action="{{ route('service-records.store', $equipment) }}">
            @csrf

            <label for="description">Popis zásahu</label>
            <textarea id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
            @error('description') <div class="error">{{ $message }}</div> @enderror

            <label for="serviced_at">Datum servisu</label>
            <input type="date" id="serviced_at" name="serviced_at"
                   value="{{ old('serviced_at', now()->format('Y-m-d')) }}" required>
            @error('serviced_at') <div class="error">{{ $message }}</div> @enderror

            <label for="cost">Cena (Kč)</label>
            {{-- step="0.01" = povolíme haléře, min="0" = nezáporná cena (kontroluje i server) --}}
            <input type="number" id="cost" name="cost" step="0.01" min="0" value="{{ old('cost', '0') }}" required>
            @error('cost') <div class="error">{{ $message }}</div> @enderror

            <p><button type="submit" class="btn btn-primary">Přidat záznam</button></p>
        </form>
    </div>
@endsection
