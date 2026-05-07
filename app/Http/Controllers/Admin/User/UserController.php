<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin-panel');

        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('manage-users');

        $roles    = User::rolesList();
        $statuses = [
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_WAIT   => 'Waiting',
        ];

        return view('admin.users.create', compact('roles', 'statuses'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('manage-users');

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
            'status'   => User::STATUS_ACTIVE,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Foydalanuvchi yaratildi.');
    }

    public function show(User $user)
    {
        Gate::authorize('admin-panel');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        Gate::authorize('manage-users');

        $roles    = User::rolesList();
        $statuses = [
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_WAIT   => 'Waiting',
        ];

        return view('admin.users.edit', compact('user', 'roles', 'statuses'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        Gate::authorize('manage-users');

        if ($request->role !== $user->role) {
            $user->changeRole($request->role);
        }

        $user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Saqlandi.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('manage-users');

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'O\'chirildi.');
    }
}
