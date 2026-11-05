@extends('layout')

@section('content')
    <h1>Seznam zařízení</h1>

    <a href="{{ route('equipment.create') }}">Přidat zařízení</a>

    {{-- filtr podle stavu --}}
    <p class="filter">
        Filtr:
        <a href="{{ route('equipment.index') }}" class="{{ $status ? '' : 'active-filter' }}">Vše</a>
        @foreach (App\Models\Equipment::STATUSES as $key => $name)
            <a href="{{ route('equipment.index', ['status' => $key]) }}" class="{{ $status == $key ? 'active-filter' : '' }}">{{ $name }}</a>
        @endforeach
    </p>

    <table>
        <tr>
            <th>Ev. číslo</th>
            <th>Název</th>
            <th>Stav</th>
            <th>Datum nákupu</th>
            <th>Náklady na servis</th>
            <th></th>
        </tr>
        @foreach ($equipment as $item)
            <tr>
                <td>{{ $item->inventory_number }}</td>
                <td>{{ $item->name }}</td>
                <td>@include('equipment._status', ['status' => $item->status])</td>
                <td>{{ $item->purchased_at->format('d.m.Y') }}</td>
                <td>{{ number_format($item->service_records_sum_cost, 2, ',', ' ') }} Kč</td>
                <td>
                    <a href="{{ route('equipment.show', $item) }}">Detail</a>
                    <a href="{{ route('equipment.edit', $item) }}">Upravit</a>
                </td>
            </tr>
        @endforeach
    </table>

    @if ($equipment->isEmpty())
        <p>Nic nenalezeno.</p>
    @endif
@endsection
