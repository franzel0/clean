<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerStatisticsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_name',
        'instrument_status_id',
        'display_name',
        'color',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function instrumentStatus()
    {
        return $this->belongsTo(InstrumentStatus::class);
    }

    public static function getDefaultSettings()
    {
        return [
            [
                'card_name' => 'card_1',
                'display_name' => 'Verfügbar',
                'color' => 'green',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'card_name' => 'card_2',
                'display_name' => 'In Wartung',
                'color' => 'yellow',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'card_name' => 'card_3',
                'display_name' => 'Defekt',
                'color' => 'red',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'card_name' => 'card_4',
                'display_name' => 'Außer Betrieb',
                'color' => 'gray',
                'is_active' => true,
                'sort_order' => 4
            ]
        ];
    }
}
