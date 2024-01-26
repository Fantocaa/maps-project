<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function edit_user(Request $request, $id): Response
    {
        // Get the user from the database
        $user = User::find($id);

        // Get the user's roles
        $roles = $user->roles->pluck('name');

        return Inertia::render('Components/Edit', [
            'user' => $user,
            'roles' => $roles, // Add this line
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update_user(UpdateUserRequest $request): RedirectResponse
    {
        $userId = $request->id; // get the user id from the request

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'role' => ['required', Rule::in(['user', 'superuser', 'admin'])],
        ]);

        $user = User::find($userId);
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        $user->roles()->detach();
        $user->assignRole($request->role);

        return Redirect::route('manage.user');
    }

    public function destroy_user($id): RedirectResponse
    {
        // $request->validate([
        //     'password' => ['required', 'current_password'],
        // ]);

        $user = User::find($id);

        $user->delete();

        return Redirect::to('/manage/user');
    }

    public function update_password($id, Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::find($id);

        if (!$user) {
            return back()->withErrors(['message' => 'User not found']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::to('/manage/user');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
