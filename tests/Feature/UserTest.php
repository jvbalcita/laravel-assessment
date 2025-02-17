<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in('Feature');

beforeEach(function () {
    $this->artisan('migrate'); // Ensure migrations are run
});

it('can display the user list', function () {
    // Arrangements
    User::factory()->count(10)->create();
    $user = User::factory()->create();

    // Actions
    $response = $this->actingAs($user)->get('/users');

    // Assertions
    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('IndexUsersPage')
        ->has('users')
    );
});

it('can create a new user', function () {
    // Arrangements
    $user = User::factory()->create();
    $attributes = [
        'prefix_name' => 'Mr.',
        'first_name' => 'John',
        'last_name' => 'Prats',
        'user_name' => 'john.prats',
        'email' => 'john.doe@gmail.com',
        'type' => 'admin',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    // Actions
    $response = $this->actingAs($user)->post('/users', $attributes);

    // Assertions
    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    $response->assertSessionHas('success', 'User created successfully.');
    $this->assertDatabaseHas('users', ['email' => 'john.doe@gmail.com']);
});

it('can update an existing user', function () {
    // Arrangements
    $user = User::factory()->create();
    $attributes = [
        'prefix_name' => 'Mr.',
        'first_name' => 'Jane',
        'last_name' => 'Prats',
        'user_name' => 'jane.prats',
        'type' => 'user',
        'email' => 'jane.prats@gmail.com',
    ];

    // Actions
    $response = $this->actingAs($user)->patch("/users/{$user->id}", $attributes);

    // Assertions
    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    $response->assertSessionHas('success', 'User updated successfully.');
    $this->assertDatabaseHas('users', ['email' => 'jane.prats@gmail.com']);
});

it('can delete a user', function () {
    // Arrangements
    $user = User::factory()->create();

    // Actions
    $response = $this->actingAs($user)->delete("/users/{$user->id}");

    // Assertions
    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    $response->assertSessionHas('success', 'User deleted successfully.');
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});
