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
        'password',
        'role',
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
}
