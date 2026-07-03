<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'username',
        'email',
        'email_verified_at',
        'password',
        'role',
        'personnel_status',
        'status_estimated_time',
        'status_note',
        'status',
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
            'status' => 'boolean',
        ];
    }

    public function assignedQueues()
    {
        return $this->hasMany(Queue::class, 'technician_user_id');
    }

    public function queueLogs()
    {
        return $this->hasMany(QueueLog::class, 'actor_user_id');
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isServiceDesk(): bool
    {
        return $this->role === 'service_desk';
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    public function canManageUsers(): bool
    {
        return $this->isSuperadmin();
    }

    public function canManageDisplaySettings(): bool
    {
        return $this->isSuperadmin();
    }

    public function canViewAllQueues(): bool
    {
        return $this->isSuperadmin() || $this->isServiceDesk();
    }

    public function canViewReports(): bool
    {
        return $this->isSuperadmin() || $this->isServiceDesk();
    }

    public function getPersonnelStatusLabelAttribute(): string
    {
        return match ($this->personnel_status) {
            'ready' => 'Ready',
            'visit' => 'Visit',
            'support_event' => 'Support Acara',
            'unavailable' => 'Tidak Tersedia',
            default => 'Ready',
        };
    }

    public function getPersonnelStatusBadgeColorAttribute(): string
    {
        return match ($this->personnel_status) {
            'ready' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'visit' => 'border-blue-200 bg-blue-50 text-blue-700',
            'support_event' => 'border-purple-200 bg-purple-50 text-purple-700',
            'unavailable' => 'border-rose-200 bg-rose-50 text-rose-700',
            default => 'border-slate-200 bg-slate-50 text-slate-700',
        };
    }

    public function getPersonnelStatusDotColorAttribute(): string
    {
        return match ($this->personnel_status) {
            'ready' => 'bg-emerald-500',
            'visit' => 'bg-blue-500',
            'support_event' => 'bg-purple-500',
            'unavailable' => 'bg-rose-500',
            default => 'bg-slate-400',
        };
    }
}
