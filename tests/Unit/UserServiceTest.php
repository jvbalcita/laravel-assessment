<?php

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class, WithFaker::class)->in('Unit');

beforeEach(function () {
    $this->userService = app(UserService::class);
    $this->artisan('migrate'); // Ensure migrations are running
});

it('can return a paginated list of users', function () {
    // Arrangements
    User::factory()->count(15)->create();
    $request = Request::create('/users', 'GET', ['per_page' => 10]);

    // Actions
    $result = $this->userService->listUsers($request);

    // Assertions
    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);
});

it('can store a user to database', function () {
    // Arrangements
    $attributes = [
        'prefix_name' => 'Mr.',
        'first_name' => 'John',
        'last_name' => 'Prats',
        'user_name' => 'john.Prats',
        'email' => 'john.prats@gmail.com',
        'type' => 'admin',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    // Create a UserStoreRequest instance
    $request = new UserStoreRequest();
    $request->merge($attributes);
    $validator = Validator::make($request->all(), $request->rules());
    $request->setValidator($validator);

    // Actions
    $createdUser = $this->userService->createUser($request);

    // Assertions
    expect($createdUser)->toBeInstanceOf(User::class)
        ->and($createdUser->email)->toBe($attributes['email']);
});

it('can find and return an existing user', function () {
    // Arrangements
    $user = User::factory()->create();

    // Actions
    $foundUser = $this->userService->find($user->id);

    // Assertions
    expect($foundUser->id)->toBe($user->id);
});

it('can update an existing user', function () {
    // Arrangements
    $user = User::factory()->create();
    $attributes = [
        'prefix_name' => 'Mr.',
        'first_name' => 'John',
        'last_name' => 'Prats',
        'user_name' => 'john.prats',
        'email' => 'john.prats@example.com',
        'type' => 'admin'
    ];

    // Create a UserUpdateRequest instance
    $request = new UserUpdateRequest();
    $request->merge($attributes);
    $validator = Validator::make($request->all(), $request->rules());
    $request->setValidator($validator);

    // Actions
    $updatedUser = $this->userService->updateUser($request, $user);

    // Assertions
    expect($updatedUser->id)->toBe($user->id)
        ->and($updatedUser->prefix_name)->toBe($attributes['prefix_name'])
        ->and($updatedUser->first_name)->toBe($attributes['first_name'])
        ->and($updatedUser->last_name)->toBe($attributes['last_name'])
        ->and($updatedUser->user_name)->toBe($attributes['user_name'])
        ->and($updatedUser->email)->toBe($attributes['email']);
});

it('can soft delete an existing user', function () {
    // Arrangements
    $user = User::factory()->create();

    // Actions
    $this->userService->deleteUser($user);

    // Assertions
    expect(User::withTrashed()->find($user->id)->trashed())->toBeTrue();
});

it('can return a paginated list of trashed users', function () {
    // Arrangements
    User::factory()->count(15)->create();
    User::query()->delete();

    // Actions
    $result = $this->userService->listTrashed(request());

    // Assertions
    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);
});

it('can restore a soft deleted user', function () {
    // Arrangements
    $user = User::factory()->create();
    $user->delete();

    // Actions
    $restoredUser = $this->userService->restore($user->id);

    // Assertions
    expect($restoredUser->trashed())->toBeFalse();
});

it('can permanently delete a soft deleted user', function () {
    // Arrangements
    $user = User::factory()->create();
    $user->delete();

    // Actions
    $this->userService->forceDelete($user->id);

    // Assertions
    expect(User::withTrashed()->find($user->id))->toBeNull();
});

it('can upload photo', function () {
    // Arrangements
    Storage::fake('public');
    $file = UploadedFile::fake()->image('photo.jpg');

    // Actions
    $path = $this->userService->upload($file, 'photos');

    // Assertions
    Storage::disk('public')->assertExists($path);
});
