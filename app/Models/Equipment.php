<?php

// namespace = slozka, ve ktere trida je (app/Models)
namespace App\Models;

// zakladni trida Model z Laravelu (Eloquent ORM)
use Illuminate\Database\Eloquent\Model;

// model pro tabulku equipment, jeden objekt = jedno zarizeni
class Equipment extends Model
{
    // equipment nema mnozne cislo, tak nazev tabulky radsi napisu rucne
    protected $table = 'equipment';

    // povolene stavy (v DB je klic, v aplikaci se ukazuje cesky nazev)
    // pouziva se ve validaci, ve filtru i u barevneho stitku
    const STATUSES = [
        'active' => 'Aktivní',
        'service' => 'V servisu',
        'retired' => 'Vyřazeno',
    ];

    // sloupce, ktere jdou vyplnit najednou pres create() nebo update()
    // ostatni (napr. id) se takhle zmenit nedaji, je to ochrana
    protected $fillable = ['name', 'inventory_number', 'status', 'purchased_at'];

    // aby purchased_at bylo datum a slo pouzit ->format()
    protected $casts = [
        'purchased_at' => 'date',
    ];

    // jedno zarizeni ma vic servisnich zaznamu (vztah 1:N)
    // seradim je od nejnovejsiho
    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class)->orderBy('serviced_at', 'desc');
    }

    // soucet vsech nakladu za servis tohohle zarizeni
    // udela v DB dotaz SELECT SUM(cost) ...
    public function totalServiceCost()
    {
        return $this->serviceRecords()->sum('cost');
    }
}
