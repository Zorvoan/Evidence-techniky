<?php

// namespace = slozka, ve ktere trida je (app/Models)
namespace App\Models;

// zakladni trida Model z Laravelu (Eloquent ORM)
use Illuminate\Database\Eloquent\Model;

// model pro tabulku service_records, jeden objekt = jeden servisni zasah
// nazev tabulky si Laravel odvodi sam (ServiceRecord -> service_records)
class ServiceRecord extends Model
{
    // sloupce, ktere jdou vyplnit najednou pres create() nebo update()
    protected $fillable = ['equipment_id', 'description', 'serviced_at', 'cost'];

    // prevod hodnot z DB
    protected $casts = [
        'serviced_at' => 'date',  // datum, aby slo pouzit ->format()
        'cost' => 'decimal:2',    // cena vzdy na 2 desetinna mista
    ];

    // servisni zaznam patri k jednomu zarizeni (vztah N:1)
    // Laravel to propoji pres sloupec equipment_id
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
