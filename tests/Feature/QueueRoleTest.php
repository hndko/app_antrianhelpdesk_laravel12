<?php

use App\Livewire\QueueManager;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('prevents inactive users from logging in', function () {
    User::factory()->create([
        'username' => 'inactiveuser',
        'password' => 'password',
        'status' => false,
    ]);

    $this->post('/login', [
        'username' => 'inactiveuser',
        'password' => 'password',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('limits technician queue list to assigned queues', function () {
    $technician = User::factory()->create([
        'name' => 'Teknisi A',
        'role' => 'technician',
        'status' => true,
    ]);

    $otherTechnician = User::factory()->create([
        'name' => 'Teknisi B',
        'role' => 'technician',
        'status' => true,
    ]);

    Queue::create([
        'queue_number' => 1,
        'user_name' => 'User Milik Teknisi',
        'laptop_id' => 'LPT-OWN-001',
        'technician_user_id' => $technician->id,
        'status' => 'waiting',
        'duration_minutes' => 30,
        'description' => 'Antrian milik teknisi login.',
    ]);

    Queue::create([
        'queue_number' => 2,
        'user_name' => 'User Teknisi Lain',
        'laptop_id' => 'LPT-OTHER-001',
        'technician_user_id' => $otherTechnician->id,
        'status' => 'waiting',
        'duration_minutes' => 30,
        'description' => 'Antrian teknisi lain.',
    ]);

    /** @var \App\Models\User $technician */
    $this->actingAs($technician);

    Livewire::test(QueueManager::class)
        ->assertSee('LPT-OWN-001')
        ->assertDontSee('LPT-OTHER-001');
});

it('writes a queue log when a queue is transferred', function () {
    $serviceDesk = User::factory()->create([
        'role' => 'service_desk',
        'status' => true,
    ]);

    $fromTechnician = User::factory()->create([
        'role' => 'technician',
        'status' => true,
    ]);

    $toTechnician = User::factory()->create([
        'role' => 'technician',
        'status' => true,
    ]);

    $queue = Queue::create([
        'queue_number' => 1,
        'user_name' => 'User Transfer',
        'laptop_id' => 'LPT-TRANSFER-001',
        'technician_user_id' => $fromTechnician->id,
        'status' => 'waiting',
        'duration_minutes' => 30,
        'description' => 'Antrian untuk transfer.',
    ]);

    /** @var \App\Models\User $serviceDesk */
    $this->actingAs($serviceDesk);

    Livewire::test(QueueManager::class)
        ->call('editQueue', $queue->id)
        ->set('technician_user_id', $toTechnician->id)
        ->call('saveQueue')
        ->assertDispatched('show-toast');

    expect($queue->fresh()->technician_user_id)->toBe($toTechnician->id);

    $this->assertDatabaseHas('queue_logs', [
        'queue_id' => $queue->id,
        'action' => 'transferred',
        'from_technician_user_id' => $fromTechnician->id,
        'to_technician_user_id' => $toTechnician->id,
        'queue_number' => 1,
        'laptop_id' => 'LPT-TRANSFER-001',
    ]);
});
