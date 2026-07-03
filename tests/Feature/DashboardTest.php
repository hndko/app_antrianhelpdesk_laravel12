<?php

use App\Livewire\Dashboard;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders operational dashboard with analytics graph and table data', function () {
    $admin = User::factory()->create([
        'role' => 'superadmin',
        'status' => true,
    ]);

    $tech = User::factory()->create([
        'name' => 'Teknisi Handal',
        'role' => 'technician',
        'status' => true,
        'personnel_status' => 'ready',
    ]);

    Queue::create([
        'queue_number' => 'A001',
        'user_name' => 'Budi User',
        'laptop_id' => 'PC-01',
        'technician_user_id' => $tech->id,
        'status' => 'progress',
        'duration_minutes' => 30,
        'description' => 'Perbaikan Jaringan',
    ]);

    /** @var \App\Models\User $admin */
    $this->actingAs($admin);

    Livewire::test(Dashboard::class)
        ->assertStatus(200)
        ->assertViewHas('stats', function ($stats) {
            return $stats['total'] === 1
                && $stats['progress'] === 1
                && $stats['active_technicians'] === 1;
        })
        ->assertViewHas('technicianPerformance', function ($perf) {
            return $perf->count() === 1 && $perf->first()->name === 'Teknisi Handal';
        })
        ->assertViewHas('recentQueues', function ($recent) {
            return $recent->count() === 1 && $recent->first()->queue_number === 'A001';
        })
        ->assertSee('Dashboard Operasional')
        ->assertSee('Tren Antrian 7 Hari Terakhir')
        ->assertSee('Komposisi Status Tiket')
        ->assertSee('Tabel Analitik Performa Teknisi')
        ->assertSee('Tabel Aktivitas Antrian Terkini')
        ->assertSee('Teknisi Handal')
        ->assertSee('A001');
});
