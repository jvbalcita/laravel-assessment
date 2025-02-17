<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserArchivedController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): Response
    {
        //Gate::authorize('update', User::class);

        return Inertia::render('IndexUsersArchivedPage', [
            'users' => $this->userService->listTrashed($request),
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $this->userService->restore($id);
        return back()->with('success', 'User restored successfully.');
    }

    /**
     * Remove the specified resource permanently from storage.
     */
    public function delete($id)
    {
        $this->userService->forceDelete($id);
        return back()->with('success', 'User deleted permanently.');
    }
}
