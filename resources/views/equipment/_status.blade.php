{{-- barevny stitek se stavem --}}
<span class="status status-{{ $status }}">{{ App\Models\Equipment::STATUSES[$status] ?? $status }}</span>
