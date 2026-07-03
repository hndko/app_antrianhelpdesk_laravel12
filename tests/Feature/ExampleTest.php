<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a successful response', function () {
    $this->withoutVite();

    $response = $this->get('/');

    $response->assertStatus(200);
});
