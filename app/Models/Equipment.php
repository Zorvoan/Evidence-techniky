<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model zařízení (nářadí).
 *
 * @property int $id
 * @property string $name
 * @property string $inventory_number
 * @property string $status
 * @property \Illuminate\Support\Carbon $purchased_at
 */
class Equipment extends Model
{
    /**
     * Název tabulky – "equipment" je v angličtině nepočitatelné, tak ho uvádíme ručně.
     */
    protected $table = 'equipment';

    /**
     * Povolené stavy zařízení: klíč = hodnota v DB, hodnota = český popisek.
     * Tento seznam používá validace i zobrazení.
     */
    public const STATUSES = [
        'active'  => 'Aktivní',
        'service' => 'V servisu',
        'retired' => 'Vyřazeno',
    ];

    /**
     * Sloupce, které lze hromadně vyplnit z formuláře.
     */
    protected $fillable = [
        'name',
        'inventory_number',
        'status',
        'purchased_at',
    ];

    /**
     * Přetypování – purchased_at bude objekt data (Carbon).
     */
    protected $casts = [
        'purchased_at' => 'date',
    ];

    /**
     * Vztah 1:N – jedno zařízení má více servisních záznamů.
     * Řadíme od nejnovějšího servisu.
     */
    public function serviceRecords(): HasMany
    {
        return $this->hasMany(ServiceRecord::class)->orderByDesc('serviced_at');
    }

    /**
     * Celkové servisní náklady tohoto zařízení (součet sloupce cost).
     */
    public function totalServiceCost(): float
    {
        return (float) $this->serviceRecords()->sum('cost');
    }

    /**
     * Český popisek stavu, např. "V servisu".
     */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
