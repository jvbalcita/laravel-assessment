<?php

use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class)->in('Feature');

beforeEach(function () {
    $this->artisan('migrate'); // Ensure migrations are running
});

it('handles the UserSaved event', function () {
    // Arrange
    Queue::fake();
    Event::fake([UserSaved::class]);

    $user = User::factory()->create();
    $userService = $this->createMock(UserService::class);
    $userService->expects($this->once())
        ->method('saveUserDetails')
        ->with($user);

    $listener = new SaveUserBackgroundInformation($userService);

    // Act
    event(new UserSaved($user));

    // Assert
    Event::assertDispatched(UserSaved::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });

    // Call the listener manually to test its functionality
    $listener->handle(new UserSaved($user));
});
