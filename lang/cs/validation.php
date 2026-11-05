<?php

// ceske chybove hlasky (jen pro pravidla, ktera v aplikaci pouzivam)
return [
    'required' => 'Pole :attribute je povinné.',
    'string' => 'Pole :attribute musí být text.',
    'numeric' => 'Pole :attribute musí být číslo.',
    'decimal' => 'Pole :attribute může mít nejvýše :decimal desetinná místa.',
    'unique' => 'Toto :attribute už existuje.',
    'in' => 'Vybraný :attribute není platný.',
    'date_format' => 'Pole :attribute musí být platné datum.',
    'after' => 'Pole :attribute musí být po :date.',
    'before_or_equal' => 'Pole :attribute nesmí být v budoucnosti.',
    'min' => [
        'numeric' => 'Pole :attribute nesmí být menší než :min.',
    ],
    'max' => [
        'string' => 'Pole :attribute může mít nejvýše :max znaků.',
    ],

    // nazvy poli v hlaskach
    'attributes' => [
        'name' => 'název',
        'inventory_number' => 'evidenční číslo',
        'status' => 'stav',
        'purchased_at' => 'datum nákupu',
        'description' => 'popis',
        'serviced_at' => 'datum servisu',
        'cost' => 'cena',
    ],
];
