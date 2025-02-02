<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Process;

it('guest', function () {
    $response = $this->get('/verified');

    $response
        ->assertOk()
        ->assertSee('Pinkary')
        ->assertSee('Become a trusted user');
});

it('auth', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/verified');

    $response
        ->assertOk()
        ->assertSee('Pinkary')
        ->assertSee('Become a trusted user');
});

it('displays login button', function () {
    $response = $this->get('/verified');

    $response
        ->assertOk()
        ->assertSee('Log In')
        ->assertDontSee('Your Profile');
});

it('displays "Your Profile" when logged in', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/verified');

    $response
        ->assertOk()
        ->assertSee('Your Profile')
        ->assertDontSee('Log In');
});

it('displays "Get Verified" when logged in and not verified', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/verified');

    $response
        ->assertOk()
        ->assertSee('Get Verified');
});

it('displays "Manage Verified Badge" when logged in and is verified', function () {
    $user = User::factory()->create([
        'is_verified' => true,
    ]);

    $this->actingAs($user);

    $response = $this->get('/verified');

    $response
        ->assertOk()
        ->assertSee('Manage Verified Badge');
});

it('displays terms of service and privacy policy', function () {
    $response = $this->get('/verified');

    $response
        ->assertOk()
        ->assertSee('Terms')
        ->assertSee('Privacy Policy')
        ->assertSee('Support')
        ->assertSee('Verified')
        ->assertSee('Brand');
});

it('displays the current version of the app', function () {
    Process::fake([
        '*' => Process::result(
            output: "v1.0.0\n",
        ),
    ]);

    $this->get('/verified')
        ->assertOk()
        ->assertSee('v1.0.0');
});
