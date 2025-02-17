<?php

use App\Events\UserSaved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class)->in('Unit');

beforeEach(function () {
    $this->artisan('migrate'); // Ensure migrations are running
});

it('dispatches the UserSaved event', function () {
    // Arrange
    Event::fake();

    $user = User::factory()->create();

    // Act
    event(new UserSaved($user));

    // Assert
    Event::assertDispatched(UserSaved::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });
});
