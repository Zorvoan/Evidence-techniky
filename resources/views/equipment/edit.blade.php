{{-- Formulář pro úpravu zařízení --}}
@extends('layout')

@section('title', 'Upravit zařízení')

@section('content')
    <div class="card">
        <h1 style="margin-top:0">Upravit: {{ $equipment->name }}</h1>

        <form method="POST" action="{{ route('equipment.update', $equipment) }}">
            @csrf
            @method('PUT') {{-- HTML formulář umí jen POST, Laravel ho takto změní na PUT --}}

            @include('equipment._form')

            <p>
                <button type="submit" class="btn btn-primary">Uložit změny</button>
                <a href="{{ route('equipment.show', $equipment) }}" class="btn">Zpět</a>
            </p>
        </form>
    </div>
@endsection
