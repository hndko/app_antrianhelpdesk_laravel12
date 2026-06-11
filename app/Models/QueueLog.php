<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueLog extends Model
{
    protected $fillable = [
        'queue_id',
        'actor_user_id',
        'from_technician_user_id',
        'to_technician_user_id',
        'from_status',
        'to_status',
        'action',
        'note',
    ];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function fromTechnician()
    {
        return $this->belongsTo(User::class, 'from_technician_user_id');
    }

    public function toTechnician()
    {
        return $this->belongsTo(User::class, 'to_technician_user_id');
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Dibuat',
            'transferred' => 'Dioper',
            'status_changed' => 'Status',
            'deleted' => 'Dihapus',
            default => 'Update',
        };
    }

    public function getDescriptionAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Dibuat untuk '.($this->toTechnician->name ?? 'teknisi'),
            'transferred' => 'Dari '.($this->fromTechnician->name ?? '-').' ke '.($this->toTechnician->name ?? '-'),
            'status_changed' => 'Status '.$this->statusLabel($this->from_status).' ke '.$this->statusLabel($this->to_status),
            'deleted' => 'Antrian dihapus',
            default => $this->note ?? 'Data diperbarui',
        };
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'waiting' => 'Menunggu',
            'progress' => 'Dikerjakan',
            'done' => 'Selesai',
            'completed' => 'Selesai',
            default => '-',
        };
    }
}
