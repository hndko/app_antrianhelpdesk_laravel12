<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\QueueLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class QueueManager extends Component
{
    use WithPagination;

    public $queue_id;
    public $laptop_id;
    public $user_name;
    public $technician_user_id;
    public $status = 'waiting';
    public $duration_minutes = 60;
    public $description;
    public $isEditing = false;
    public $technicians;
    public $queuePendingDeleteId;

    public function mount()
    {
        $this->loadTechnicians();
    }

    public function loadTechnicians()
    {
        $this->technicians = User::query()
            ->where('role', 'technician')
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }

    public function resetQueueForm()
    {
        $this->queue_id = null;
        $this->user_name = '';
        $this->laptop_id = '';
        $this->technician_user_id = null;
        $this->status = 'waiting';
        $this->duration_minutes = 60;
        $this->description = '';
        $this->isEditing = false;
    }

    public function saveQueue()
    {
        $user = Auth::user();

        if ($user->isTechnician() && ! $this->technician_user_id) {
            $this->technician_user_id = $user->id;
        }

        $validated = $this->validate([
            'user_name' => 'nullable|string|max:255',
            'laptop_id' => 'required|string|max:255',
            'technician_user_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'technician')->where('status', true),
            ],
            'status' => ['required', Rule::in(['waiting', 'progress', 'done'])],
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($this->isEditing) {
            $queue = $this->findQueueForUser($this->queue_id);

            DB::transaction(function () use ($queue, $validated, $user) {
                $oldTechnicianId = $queue->technician_user_id;
                $oldStatus = $queue->status;

                $queue->update($validated);

                if ((int) $oldTechnicianId !== (int) $validated['technician_user_id']) {
                    $this->writeQueueLog($queue, $user, 'transferred', $oldTechnicianId, $validated['technician_user_id'], $oldStatus, $validated['status']);
                }

                if ($oldStatus !== $validated['status']) {
                    $this->writeQueueLog($queue, $user, 'status_changed', $oldTechnicianId, $validated['technician_user_id'], $oldStatus, $validated['status']);
                }

                if ((int) $oldTechnicianId === (int) $validated['technician_user_id'] && $oldStatus === $validated['status']) {
                    $this->writeQueueLog($queue, $user, 'updated', $oldTechnicianId, $validated['technician_user_id'], $oldStatus, $validated['status']);
                }
            });

            $msg = 'Data antrian berhasil diperbarui!';
        } else {
            DB::transaction(function () use ($validated, $user) {
                $lastQueue = Queue::whereDate('created_at', Carbon::today())
                    ->lockForUpdate()
                    ->max('queue_number') ?? 0;

                $queue = Queue::create($validated + [
                    'queue_number' => $lastQueue + 1,
                ]);

                $this->writeQueueLog($queue, $user, 'created', null, $validated['technician_user_id'], null, $validated['status']);
            });

            $msg = 'Antrian baru berhasil ditambahkan!';
        }

        $this->resetQueueForm();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => $msg,
        ]);
    }

    public function editQueue($id)
    {
        $queue = $this->findQueueForUser($id);
        $this->queue_id = $queue->id;
        $this->user_name = $queue->user_name;
        $this->laptop_id = $queue->laptop_id;
        $this->technician_user_id = $queue->technician_user_id;
        $this->status = $queue->status;
        $this->duration_minutes = $queue->duration_minutes;
        $this->description = $queue->description;
        $this->isEditing = true;
    }

    public function askDeleteQueue($id): void
    {
        abort_unless(Auth::user()->canViewAllQueues(), 403);

        $this->queuePendingDeleteId = $this->queueQueryForUser()->findOrFail($id)->id;
    }

    public function cancelDeleteQueue(): void
    {
        $this->queuePendingDeleteId = null;
    }

    public function confirmDeleteQueue()
    {
        abort_unless(Auth::user()->canViewAllQueues(), 403);

        $queue = $this->queueQueryForUser()->findOrFail($this->queuePendingDeleteId);

        DB::transaction(function () use ($queue) {
            $this->writeQueueLog($queue, Auth::user(), 'deleted', $queue->technician_user_id, null, $queue->status, null);

            $queue->delete();
        });

        $this->queuePendingDeleteId = null;

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Antrian berhasil dihapus.',
        ]);
    }

    public function paginationView()
    {
        return 'components.pagination-custom';
    }

    private function queueQueryForUser()
    {
        $query = Queue::query();

        if (Auth::user()->isTechnician()) {
            $query->where('technician_user_id', Auth::id());
        }

        return $query;
    }

    private function findQueueForUser($id): Queue
    {
        return $this->queueQueryForUser()->findOrFail($id);
    }

    private function writeQueueLog(
        Queue $queue,
        User $actor,
        string $action,
        $fromTechnicianId = null,
        $toTechnicianId = null,
        ?string $fromStatus = null,
        ?string $toStatus = null
    ): void {
        QueueLog::create([
            'queue_id' => $queue->id,
            'actor_user_id' => $actor->id,
            'from_technician_user_id' => $fromTechnicianId,
            'to_technician_user_id' => $toTechnicianId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'action' => $action,
            'note' => $this->queueLogNote($action),
            'queue_number' => $queue->queue_number,
            'user_name' => $queue->user_name,
            'laptop_id' => $queue->laptop_id,
        ]);
    }

    private function queueLogNote(string $action): string
    {
        return match ($action) {
            'created' => 'Antrian dibuat.',
            'transferred' => 'Antrian dioper ke teknisi lain.',
            'status_changed' => 'Status antrian diperbarui.',
            'deleted' => 'Antrian dihapus.',
            default => 'Data antrian diperbarui.',
        };
    }

    public function render()
    {
        $queues = $this->queueQueryForUser()
            ->with([
                'technician',
                'logs' => fn ($query) => $query
                    ->with(['actor', 'fromTechnician', 'toTechnician'])
                    ->latest(),
            ])
            ->where(function ($query) {
                $query->whereNotIn('status', Queue::doneStatuses())
                    ->orWhere(function ($sub) {
                        $sub->whereIn('status', Queue::doneStatuses())
                            ->whereDate('updated_at', Carbon::today());
                    });
            })
            ->orderByRaw("CASE status WHEN 'progress' THEN 1 WHEN 'waiting' THEN 2 WHEN 'done' THEN 3 ELSE 4 END")
            ->orderBy('queue_number', 'asc')
            ->paginate(5);

        return view('livewire.queue-manager', [
            'queues' => $queues,
            'canTransferQueue' => true,
            'canDeleteQueue' => Auth::user()->canViewAllQueues(),
        ])->layout('components.app-backend');
    }
}
