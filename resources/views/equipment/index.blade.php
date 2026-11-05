@extends('layout')

@section('content')
    <div class="page-head">
        <div>
            <h1>Zařízení</h1>
            <p class="muted">Počet zařízení: {{ $equipment->count() }}</p>
        </div>
        <a href="{{ route('equipment.create') }}" class="btn btn-primary">+ Přidat zařízení</a>
    </div>

    {{-- filtr podle stavu --}}
    <div class="filter">
        <a href="{{ route('equipment.index') }}" class="{{ $status ? '' : 'active-filter' }}">Vše</a>
        @foreach (App\Models\Equipment::STATUSES as $key => $name)
            <a href="{{ route('equipment.index', ['status' => $key]) }}" class="{{ $status == $key ? 'active-filter' : '' }}">{{ $name }}</a>
        @endforeach
    </div>

    <div class="card table-wrap">
        <table>
            <tr>
                <th>Ev. číslo</th>
                <th>Název</th>
                <th>Stav</th>
                <th>Datum nákupu</th>
                <th class="right">Náklady na servis</th>
                <th></th>
            </tr>
            @forelse ($equipment as $item)
                <tr>
                    <td><span class="code">{{ $item->inventory_number }}</span></td>
                    <td><a href="{{ route('equipment.show', $item) }}"><b>{{ $item->name }}</b></a></td>
                    <td>@include('equipment._status', ['status' => $item->status])</td>
                    <td>{{ $item->purchased_at->format('d.m.Y') }}</td>
                    <td class="right">{{ number_format($item->service_records_sum_cost, 2, ',', ' ') }} Kč</td>
                    <td class="right">
                        <a href="{{ route('equipment.edit', $item) }}" class="btn btn-small">Upravit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Nic nenalezeno.</td></tr>
            @endforelse
        </table>
    </div>
@endsection
