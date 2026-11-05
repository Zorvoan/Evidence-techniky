@extends('layout')

@section('content')
    <h1>Nové zařízení</h1>

    <form method="POST" action="{{ route('equipment.store') }}">
        @csrf
        @include('equipment._form')
        <button type="submit">Uložit</button>
    </form>

    <p><a href="{{ route('equipment.index') }}">Zpět</a></p>
@endsection
