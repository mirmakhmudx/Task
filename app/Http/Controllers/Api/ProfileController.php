<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): ProfileResource
    {
        return new ProfileResource($request->user());
    }

    public function update(Request $request): ProfileResource
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:255', 'regex:/^\+?\d+$/'],
        ]);

        $request->user()->update($data);

        return new ProfileResource($request->user()->fresh());
    }
}
