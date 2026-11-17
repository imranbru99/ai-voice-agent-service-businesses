<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'organization_name', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && $this->role?->canAccessAdmin();
    }

    public function ownedLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'owner_id');
    }

    public function handledCalls(): HasMany
    {
        return $this->hasMany(VoiceCall::class, 'handled_by');
    }

    public function assignedBookings(): HasMany
    {
        return $this->hasMany(JobBooking::class, 'assigned_to');
    }

    public function isDemo(): bool
    {
        return $this->role === UserRole::Demo;
    }
}
