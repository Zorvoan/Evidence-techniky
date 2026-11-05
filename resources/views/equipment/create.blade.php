{{-- Formulář pro nové zařízení --}}
@extends('layout')

@section('title', 'Nové zařízení')

@section('content')
    <div class="card">
        <h1 style="margin-top:0">Nové zařízení</h1>

        <form method="POST" action="{{ route('equipment.store') }}">
            @csrf {{-- ochrana proti CSRF útoku --}}

            @include('equipment._form')

            <p>
                <button type="submit" class="btn btn-primary">Uložit</button>
                <a href="{{ route('equipment.index') }}" class="btn">Zpět</a>
            </p>
        </form>
    </div>
@endsection
