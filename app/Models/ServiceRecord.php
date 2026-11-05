<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
    protected $fillable = ['equipment_id', 'description', 'serviced_at', 'cost'];

    protected $casts = [
        'serviced_at' => 'date',
        'cost' => 'decimal:2',
    ];

    // servisni zaznam patri k jednomu zarizeni
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
