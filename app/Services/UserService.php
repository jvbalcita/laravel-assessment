<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class UserService
{
    /**
     * The model instance.
     *
     * @var User
     */
    protected User $model;

    /**
     * The request instance.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * Constructor to bind model to a repository.
     *
     * @param User $model
     * @param Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * Retrieve all resources and paginate.
     */
    public function listUsers(Request $request)
    {
        return $this->model->query()
            ->paginate($request->query('per_page', default_per_page()));
    }

    /**
     * Create a new user.
     *
     * @param UserStoreRequest $request
     * @return User
     */
    public function createUser(UserStoreRequest $request): User
    {
        $validated = $request->validated();
        return $this->model->create($validated);
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Update an existing user.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return User
     */
    public function updateUser(UserUpdateRequest $request, User $user): User
    {
        $validated = $request->validated();
        $user->update($validated);
        return $user;
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    /**
     * Retrieve all resources and paginate.
     */
    public function listTrashed(Request $request)
    {
        return $this->model->onlyTrashed()
            ->paginate($request->query('per_page', default_per_page()));
    }

    /**
     * Restore model resource.
     *
     * @param $id
     * @return User
     */
    public function restore($id): User
    {
        $user = $this->model->onlyTrashed()->findOrFail($id);
        $user->restore();
        return $user;
    }

    /**
     * Permanently delete model resource.
     *
     * @param $id
     * @return void
     */
    public function forceDelete($id): void
    {
        $user = $this->model->onlyTrashed()->findOrFail($id);
        $user->forceDelete();
    }

    /**
     * Upload a file.
     *
     * @param UploadedFile $file
     * @param $directory
     * @return string
     */
    public function upload(UploadedFile $file, $directory): string
    {
        return $file->store($directory, 'public');
    }

    /**
     * Save user details to the details table.
     *
     * @param User $user
     * @return void
     */
    public function saveUserDetails(User $user): void
    {
        $details = [
            [
                'key' => 'Full name',
                'value' => $user->full_name,
                'type' => 'bio',
                'user_id' => $user->id,
            ],
            [
                'key' => 'Middle Initial',
                'value' => $user->middle_initial,
                'type' => 'bio',
                'user_id' => $user->id,
            ],
            [
                'key' => 'Avatar',
                'value' => $user->avatar,
                'type' => 'bio',
                'user_id' => $user->id,
            ],
            [
                'key' => 'Gender',
                'value' => $this->getGender($user->prefix_name),
                'type' => 'bio',
                'user_id' => $user->id,
            ],
        ];

        foreach ($details as $detail) {
            Detail::updateOrCreate(
                ['key' => $detail['key'], 'user_id' => $user->id],
                $detail
            );
        }
    }

    /**
     * Determine the gender based on the prefix name.
     *
     * @param string $prefixName
     * @return string
     */
    protected function getGender(string $prefixName): string
    {
        return match (strtolower($prefixName)) {
            'mr' => 'Male',
            'ms', 'mrs' => 'Female',
            default => 'Unknown',
        };
    }
}
