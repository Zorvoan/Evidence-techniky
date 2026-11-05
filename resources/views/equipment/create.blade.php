@extends('layout')

@section('content')
    <div class="page-head">
        <h1>Nové zařízení</h1>
    </div>

    <div class="card form-card">
        <form method="POST" action="{{ route('equipment.store') }}">
            @csrf
            @include('equipment._form')

            <div class="actions">
                <button type="submit" class="btn btn-primary">Uložit</button>
                <a href="{{ route('equipment.index') }}" class="btn">Zrušit</a>
            </div>
        </form>
    </div>
@endsection
