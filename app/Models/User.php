<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'business_id',
        'branch_id',
        'name',
        'email',
        'phone',
        'locale',
        'password',
        'is_platform_admin',
        'is_demo',
        'panel_role',
        'meta',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_admin' => 'boolean',
            'is_demo' => 'boolean',
            'meta' => 'array',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'platform' => (bool) $this->is_platform_admin,
            'business' => in_array($this->panel_role, ['owner', 'manager', 'accountant', 'hr', 'marketing', 'support', 'warehouse'], true)
                || $this->is_platform_admin,
            'agent' => in_array($this->panel_role, ['agent', 'owner', 'manager'], true) || $this->is_platform_admin,
            'rider' => in_array($this->panel_role, ['rider', 'owner', 'manager'], true) || $this->is_platform_admin,
            'admin' => $this->is_platform_admin,
            default => false,
        };
    }
}
