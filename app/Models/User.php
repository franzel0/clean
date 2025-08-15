<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function reportedDefects(): HasMany
    {
        return $this->hasMany(DefectReport::class, 'reported_by');
    }

    public function acknowledgedDefects(): HasMany
    {
        return $this->hasMany(DefectReport::class, 'acknowledged_by');
    }

    public function resolvedDefects(): HasMany
    {
        return $this->hasMany(DefectReport::class, 'resolved_by');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'requested_by');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InstrumentMovement::class, 'performed_by');
    }

    public function getRoleDisplayAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'sterilization_staff' => 'Sterilisations-Personal',
            'or_staff' => 'OP-Personal',
            'purchasing_staff' => 'Einkauf',
            'user' => 'Benutzer',
            default => ucfirst($this->role),
        };
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function canManageInstruments(): bool
    {
        return in_array($this->role, ['admin', 'sterilization_staff']);
    }

    public function canReportDefects(): bool
    {
        return in_array($this->role, ['admin', 'or_staff', 'sterilization_staff']);
    }

    public function canManagePurchases(): bool
    {
        return in_array($this->role, ['admin', 'purchasing_staff']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
