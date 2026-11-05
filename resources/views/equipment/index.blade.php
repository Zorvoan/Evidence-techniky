{{-- Seznam zařízení s filtrem podle stavu --}}
@extends('layout')

@section('title', 'Seznam zařízení')

@section('content')
    <div class="card">
        <h1 style="margin-top:0">Zařízení</h1>

        {{-- Tlačítko pro přidání nového zařízení --}}
        <p><a href="{{ route('equipment.create') }}" class="btn btn-primary">+ Přidat zařízení</a></p>

        {{-- Filtr podle stavu: "Vše" + jedno tlačítko pro každý povolený stav --}}
        <p>
            Filtr:
            <a href="{{ route('equipment.index') }}" class="btn {{ $status === null ? 'selected' : '' }}">Vše</a>
            @foreach (\App\Models\Equipment::STATUSES as $key => $label)
                <a href="{{ route('equipment.index', ['status' => $key]) }}"
                   class="btn {{ $status === $key ? 'selected' : '' }}">{{ $label }}</a>
            @endforeach
        </p>

        {{-- Tabulka zařízení --}}
        <table>
            <thead>
                <tr>
                    <th>Ev. číslo</th>
                    <th>Název</th>
                    <th>Stav</th>
                    <th>Zakoupeno</th>
                    <th class="right">Servis celkem</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($equipment as $item)
                    <tr>
                        <td>{{ $item->inventory_number }}</td>
                        <td><a href="{{ route('equipment.show', $item) }}">{{ $item->name }}</a></td>
                        <td>@include('equipment._status', ['status' => $item->status])</td>
                        <td>{{ $item->purchased_at->format('d.m.Y') }}</td>
                        {{-- service_records_sum_cost doplnil withSum() v controlleru --}}
                        <td class="right">{{ number_format($item->service_records_sum_cost ?? 0, 2, ',', ' ') }} Kč</td>
                        <td class="right">
                            <a href="{{ route('equipment.show', $item) }}" class="btn">Detail</a>
                            <a href="{{ route('equipment.edit', $item) }}" class="btn">Upravit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Žádná zařízení nenalezena.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
