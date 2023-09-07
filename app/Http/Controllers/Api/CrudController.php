<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CrudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (Cache::has('users')) {
                $users = Cache::get('users');
            } else {
                $users = User::all();
                Cache::put('users', $users, 3600);
            }

            return response()->json([
                'code' => 200,
                'message' => 'OK',
                'data' => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Bad Request',
                'error' => $validator->errors()
            ], 400);
        }

        $validated = $validator->validated();

        try {
            $user = User::create($validated);

            if (Cache::has('users')) {
                $users = Cache::get('users');
                $users->push($user);
                Cache::put('users', $users, 3600);
            }

            return response()->json([
                'code' => 201,
                'message' => 'Created',
                'data' => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            if (Cache::has('users')) {
                $user = Cache::get('users')->where('id', $id)->first();
            } else {
                $user = User::find($id);
            }

            if ($user) {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'Not Found',
                    'error' => 'User not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Cache::has('users')) {
            $user = Cache::get('users')->where('id', $id)->first();
        } else {
            $user = User::find($id);
        }

        if (!$user) {
            return response()->json([
                'code' => 404,
                'message' => 'Not Found',
                'error' => 'User not found'
            ], 404);
        }

        $validator = validator($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Bad Request',
                'error' => $validator->errors()
            ], 400);
        }

        $validated = $validator->validated();

        try {
            $user->update($validated);

            if (Cache::has('users')) {
                $users = Cache::get('users');
                $users->where('id', $id)->first()->update($validated);
                Cache::put('users', $users, 3600);
            }

            return response()->json([
                'code' => 200,
                'message' => 'OK',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $user->delete();

                if (Cache::has('users')) {
                    $users = Cache::get('users')->where('id', '!=', $id);
                    Cache::put('users', $users, 3600);
                }

                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => 'User deleted'
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'Not Found',
                    'error' => 'User not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Internal Server Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
