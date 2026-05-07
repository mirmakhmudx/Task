<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UsersUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        $this->authorize('admin-panel');

        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('manage-users');
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['status'] = User::STATUS_ACTIVE;
        $user = User::create($data);
        return redirect()->route('admin.users.show', $user);
    }


    public function show(User $user)
    {

        return view('admin.users.show', compact('user', ));
    }


    public function edit(User $user)
    {
        $statuses = [User::STATUS_WAIT => "Waiting", User::STATUS_ACTIVE => "Active"];
        return view('admin.users.edit', compact('user', 'statuses'));
    }

    public function update(UsersUpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        return redirect()->route('admin.users.show', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}
