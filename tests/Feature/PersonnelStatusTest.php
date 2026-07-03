<?php

use App\Livewire\PersonnelStatusSwitcher;
use App\Livewire\PublicDisplay;
use App\Livewire\UserManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('allows technician to update their own personnel status via PersonnelStatusSwitcher', function () {
    $technician = User::factory()->create([
        'role' => 'technician',
        'status' => true,
        'personnel_status' => 'ready',
    ]);

    $this->actingAs($technician);

    Livewire::test(PersonnelStatusSwitcher::class)
        ->set('personnel_status', 'visit')
        ->set('status_estimated_time', '15:00 WIB')
        ->set('status_note', 'Kunjungan ke lantai 3')
        ->call('saveStatus')
        ->assertDispatched('show-toast');

    $technician->refresh();
    expect($technician->personnel_status)->toBe('visit');
    expect($technician->status_estimated_time)->toBe('15:00 WIB');
    expect($technician->status_note)->toBe('Kunjungan ke lantai 3');
});

it('allows superadmin to manage user personnel status via UserManager', function () {
    $superadmin = User::factory()->create([
        'role' => 'superadmin',
        'status' => true,
    ]);

    $technician = User::factory()->create([
        'name' => 'Budi Teknisi',
        'username' => 'buditek',
        'email' => 'budi@example.com',
        'role' => 'technician',
        'status' => true,
        'personnel_status' => 'ready',
    ]);

    $this->actingAs($superadmin);

    Livewire::test(UserManager::class)
        ->call('edit', $technician->id)
        ->set('personnel_status', 'support_event')
        ->set('status_estimated_time', '16:00 WIB')
        ->call('save')
        ->assertDispatched('show-toast');

    $technician->refresh();
    expect($technician->personnel_status)->toBe('support_event');
    expect($technician->status_estimated_time)->toBe('16:00 WIB');
});

it('displays active technicians on public display', function () {
    $technician = User::factory()->create([
        'name' => 'Teknisi Handal',
        'role' => 'technician',
        'status' => true,
        'personnel_status' => 'ready',
    ]);

    Livewire::test(PublicDisplay::class)
        ->assertSee('Teknisi Handal')
        ->assertSee('Ready');
});
