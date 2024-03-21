<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequset;
use App\Http\Requests\RegisterRequset;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function Register(RegisterRequset $request)
    {
        $data = $request->validated();

        // Create the user
        $user = User::create($data);

        // Generate a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Set the response data
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        // Return the response
        return response($response, 201);
    }

    public function Login(LoginRequset $request)
    {
        // Validate the request data
        $validatedData = $request->validate();

        // Attempt to log the user in
        if (!auth()->attempt($validatedData)) {
            return response(['message' => 'Invalid credentials']);
        }

        // Get the user
        $user = User::where('phone', $validatedData['phone'])->first();

        // Generate a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Set the response data
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        // Return the response
        return response($response, 201);
    }
}
