<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model servisního záznamu (jeden servisní zásah u zařízení).
 *
 * @property int $id
 * @property int $equipment_id
 * @property string $description
 * @property \Illuminate\Support\Carbon $serviced_at
 * @property string $cost
 */
class ServiceRecord extends Model
{
    /**
     * Sloupce, které lze hromadně vyplnit z formuláře.
     */
    protected $fillable = [
        'equipment_id',
        'description',
        'serviced_at',
        'cost',
    ];

    /**
     * Přetypování – datum jako Carbon, cena jako desetinné číslo se 2 místy.
     */
    protected $casts = [
        'serviced_at' => 'date',
        'cost'        => 'decimal:2',
    ];

    /**
     * Vztah N:1 – servisní záznam patří k jednomu zařízení.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
