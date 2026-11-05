@extends('layout')

@section('content')
    <div class="page-head">
        <h1>Upravit zařízení</h1>
    </div>

    <div class="card form-card">
        <form method="POST" action="{{ route('equipment.update', $equipment) }}">
            @csrf
            @method('PUT')
            @include('equipment._form')

            <div class="actions">
                <button type="submit" class="btn btn-primary">Uložit změny</button>
                <a href="{{ route('equipment.show', $equipment) }}" class="btn">Zrušit</a>
            </div>
        </form>
    </div>
@endsection
