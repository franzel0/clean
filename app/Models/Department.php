<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function operatingRooms(): HasMany
    {
        return $this->hasMany(OperatingRoom::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class, 'current_location_id');
    }

    public function defectReports(): HasMany
    {
        return $this->hasMany(DefectReport::class, 'reporting_department_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
