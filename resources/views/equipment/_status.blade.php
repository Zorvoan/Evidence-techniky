{{--
    Štítek stavu zařízení – barevně i textově odlišený.
    Použití: @include('equipment._status', ['status' => $item->status])
--}}
<span class="badge badge-{{ $status }}">
    {{ \App\Models\Equipment::STATUSES[$status] ?? $status }}
</span>
