<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    // equipment nema mnozne cislo, tak nazev tabulky radsi napisu rucne
    protected $table = 'equipment';

    // povolene stavy (v DB je klic, v aplikaci se ukazuje cesky nazev)
    const STATUSES = [
        'active' => 'Aktivní',
        'service' => 'V servisu',
        'retired' => 'Vyřazeno',
    ];

    protected $fillable = ['name', 'inventory_number', 'status', 'purchased_at'];

    // aby purchased_at bylo datum a slo pouzit ->format()
    protected $casts = [
        'purchased_at' => 'date',
    ];

    // jedno zarizeni ma vic servisnich zaznamu
    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class)->orderBy('serviced_at', 'desc');
    }

    // soucet vsech nakladu za servis
    public function totalServiceCost()
    {
        return $this->serviceRecords()->sum('cost');
    }
}
