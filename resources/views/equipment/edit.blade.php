@extends('layout')

@section('content')
    <h1>Upravit zařízení</h1>

    <form method="POST" action="{{ route('equipment.update', $equipment) }}">
        @csrf
        @method('PUT')
        @include('equipment._form')
        <button type="submit">Uložit</button>
    </form>

    <p><a href="{{ route('equipment.show', $equipment) }}">Zpět</a></p>
@endsection
