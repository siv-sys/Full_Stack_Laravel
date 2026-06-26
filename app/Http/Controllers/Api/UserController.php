<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->role !== null && $request->role !== '', function ($q) use ($request) {
                $q->where('is_admin', $request->role === 'admin');
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'items' => UserResource::collection($users),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ],
            'message' => 'Users retrieved.',
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->role === 'admin',
        ]);

        return $this->success(new UserResource($user), 'User created.', 201);
    }

    public function show(User $user)
    {
        return $this->success(new UserResource($user), 'User retrieved.');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if ($user->id === auth()->id() && $request->role === 'customer' && $user->is_admin) {
            return $this->error('You cannot demote yourself.', 403);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->role === 'admin',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return $this->success(new UserResource($user), 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return $this->error('You cannot delete your own account.', 403);
        }

        $adminCount = User::where('is_admin', true)->count();
        if ($user->is_admin && $adminCount <= 1) {
            return $this->error('Cannot delete the last admin user.', 403);
        }

        $user->delete();

        return $this->success(null, 'User deleted.');
    }
}
