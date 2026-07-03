<?php

use App\Livewire\DailyReport;
use App\Models\Queue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('defaults to displaying all completed tickets across technicians when no technician is selected', function () {
    $superadmin = User::factory()->create([
        'role' => 'superadmin',
        'status' => true,
    ]);

    $tech1 = User::factory()->create(['role' => 'technician', 'status' => true, 'name' => 'Tech One']);
    $tech2 = User::factory()->create(['role' => 'technician', 'status' => true, 'name' => 'Tech Two']);

    Queue::create([
        'queue_number' => 'A001',
        'user_name' => 'User A',
        'laptop_id' => 'Laptop A',
        'technician_user_id' => $tech1->id,
        'status' => 'done',
        'duration_minutes' => 30,
        'description' => 'Fix OS',
    ]);

    Queue::create([
        'queue_number' => 'A002',
        'user_name' => 'User B',
        'laptop_id' => 'Laptop B',
        'technician_user_id' => $tech2->id,
        'status' => 'done',
        'duration_minutes' => 45,
        'description' => 'Replace RAM',
    ]);

    $this->actingAs($superadmin);

    Livewire::test(DailyReport::class)
        ->assertSet('reportData', 2)
        ->assertSee('Semua Teknisi')
        ->assertSee('A001')
        ->assertSee('A002')
        ->set('selectedTechnician', $tech1->id)
        ->call('generateReport')
        ->assertSet('reportData', 1)
        ->assertSee('A001')
        ->assertDontSee('A002');
});
