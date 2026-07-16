<?php

use App\Models\User;
use App\Services\LdapService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows local users to log in normally', function () {
    $user = User::factory()->create([
        'username' => 'localuser',
        'password' => 'password',
        'auth_source' => 'local',
        'status' => true,
    ]);

    $this->post('/login', [
        'username' => 'localuser',
        'password' => 'password',
    ])->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});

it('allows whitelisted and active AD users to log in when AD credentials are correct', function () {
    $user = User::factory()->create([
        'username' => 'aduser',
        'password' => null,
        'auth_source' => 'ad',
        'status' => true,
    ]);

    // Mock LDAP Service
    $this->mock(LdapService::class, function ($mock) {
        $mock->shouldReceive('authenticate')
            ->once()
            ->with('aduser', 'correct_ad_password')
            ->andReturn(true);
    });

    $this->post('/login', [
        'username' => 'aduser',
        'password' => 'correct_ad_password',
    ])->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});

it('prevents non-whitelisted AD users from logging in even with correct AD credentials', function () {
    // We mock the LDAP service, but the user is NOT in the database (not whitelisted)
    $this->mock(LdapService::class, function ($mock) {
        $mock->shouldReceive('authenticate')->never();
    });

    $this->post('/login', [
        'username' => 'nonexistent_ad_user',
        'password' => 'some_password',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('prevents whitelisted AD users from logging in when AD credentials are incorrect', function () {
    User::factory()->create([
        'username' => 'aduser',
        'password' => null,
        'auth_source' => 'ad',
        'status' => true,
    ]);

    // Mock LDAP Service returning false
    $this->mock(LdapService::class, function ($mock) {
        $mock->shouldReceive('authenticate')
            ->once()
            ->with('aduser', 'wrong_ad_password')
            ->andReturn(false);
    });

    $this->post('/login', [
        'username' => 'aduser',
        'password' => 'wrong_ad_password',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('prevents inactive whitelisted AD users from logging in', function () {
    User::factory()->create([
        'username' => 'aduser_inactive',
        'password' => null,
        'auth_source' => 'ad',
        'status' => false,
    ]);

    $this->mock(LdapService::class, function ($mock) {
        $mock->shouldReceive('authenticate')->never();
    });

    $this->post('/login', [
        'username' => 'aduser_inactive',
        'password' => 'some_password',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('allows batch adding AD users from search results', function () {
    $admin = User::factory()->create([
        'role' => 'superadmin',
        'status' => true,
    ]);

    $this->actingAs($admin);

    $adSearchResults = [
        ['name' => 'AD User One', 'username' => 'aduser1', 'email' => 'aduser1@kpk.go.id'],
        ['name' => 'AD User Two', 'username' => 'aduser2', 'email' => 'aduser2@kpk.go.id'],
    ];

    Livewire\Livewire::test(\App\Livewire\UserManager::class)
        ->set('adSearchResults', $adSearchResults)
        ->call('toggleSelectAdUser', 'aduser1')
        ->call('toggleSelectAdUser', 'aduser2')
        ->set('bulkRole', 'service_desk')
        ->set('bulkStatus', true)
        ->call('addSelectedAdUsers')
        ->assertDispatched('show-toast');

    $this->assertDatabaseHas('users', [
        'username' => 'aduser1',
        'email' => 'aduser1@kpk.go.id',
        'role' => 'service_desk',
        'auth_source' => 'ad',
    ]);

    $this->assertDatabaseHas('users', [
        'username' => 'aduser2',
        'email' => 'aduser2@kpk.go.id',
        'role' => 'service_desk',
        'auth_source' => 'ad',
    ]);
});
