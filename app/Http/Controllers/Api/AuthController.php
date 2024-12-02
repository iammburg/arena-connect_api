<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $register_data = new User();
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone_number' => ['required', 'regex:/^(0|\+62)[0-9]{9,15}$/'],
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $register_data->name = $request->name;
        $register_data->email = $request->email;
        $register_data->phone_number = $request->phone_number;
        $register_data->password = bcrypt($request->password);

        $register_data->save();

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $register_data,
        ], 201);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Log-in failed',
                'data' => $validator->errors(),
            ], 422);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        $login_data = User::where('email', $request->email)->first();
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $login_data->createToken('auth_token')->plainTextToken,
            'user' => $login_data
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'message' => 'List of all users',
            'data' => $users,
        ], 200);
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255',
            'password'  => 'string',
            // 'role'      => 'required',
        ]);

        $updateUser = User::find($id);

        if ($request->password) {
            $updateUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'role' => $request->role,
            ]);
        } else {
            $updateUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->old_password,
                // 'role' => $request->role,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $updateUser,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
