<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;


class ApiController extends Controller
{
    // Register API [POST, formdata]
    public function register(Request $request) {

        //Data Validation
        // $request->validate([
        //     "name" => "required",
        //     "email" => "required|email|unique:users",
        //     "password" => "required|confirmed"
        // ]);

         // Validate the incoming request data
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:2|confirmed',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Data save
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);


        event(new Registered($user));
        // Response
        return response()->json([
            "status" => true,
            "message" => "User created successfully"
        ]);
    }

    // Login API [POST, formdata]
    public function login(Request $request) {
        // data validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // JWTAuth and attempt
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        // Response
        if(!empty($token)) {
            return response()->json([
                "status" => true,
                "message" => "User logged in successfully",
                "token" => $token,
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid login details"
        ]);
    }

    //Profile API [GET]
    public function profile() {
        $userData = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "user" => $userData
        ]);
    }

    //Refresh Token API [GET]
    public function refreshToken() {
        $newToken = auth()->refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token generated",
            "token" => $newToken
        ]);
    }

    //Logout Token [GET]
    public function logout() {
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "User logged out successfully!"
        ]);
    }
}
