<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_number',
        'user_name',
        'laptop_id',
        'technician_user_id',
        'status',
        'duration_minutes',
        'description',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_user_id');
    }

    public function technicianUser()
    {
        return $this->belongsTo(User::class, 'technician_user_id');
    }

    public function logs()
    {
        return $this->hasMany(QueueLog::class);
    }

    public static function doneStatuses(): array
    {
        return ['done'];
    }

    // Helper untuk badge warna status (nanti dipakai di blade)
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'waiting' => 'bg-gray-500', // Sesuaikan dengan Tailwind nanti
            'progress' => 'bg-yellow-500',
            'done' => 'bg-green-500',
            default => 'bg-gray-500',
        };
    }

    // Helper untuk Label Bahasa Indonesia
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'waiting' => 'Menunggu',
            'progress' => 'Dikerjakan',
            'done' => 'Selesai',
            default => '-',
        };
    }
}
