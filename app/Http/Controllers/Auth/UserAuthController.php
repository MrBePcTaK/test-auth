<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    /**
     * User registration
     */
    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);
        $data['email_verified_at'] = now();
        
        if($validator->fails()){
            return response(['message' => 'Validation Error',
            'error' => $validator->errors()]);
        } else {
            $user = new User;
            $user->name = $data['name'];
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $response = Http::post('webserver/oauth/token', [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
                'username' => $data['email'],
                'password' => $data['password'],
                'scope' => '',
            ]);
    
            $user['token'] = $response->json();
    
            return response()->json([
                'success' => true,
                'statusCode' => 201,
                'message' => 'User has been registered successfully.',
                'data' => $user,
            ], 201);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $response = Http::post('webserver/oauth/token', [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ]);

            $user['token'] = $response->json();

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'User has been logged successfully.',
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

    }

    /**
     * User profile
     */
    public function me()
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Authenticated use info.',
            'data' => $user,
        ], 200);
    }

    /**
     * Refresh token
     */
    public function refreshToken(Request $request)
    {
        $response = Http::post('webserver/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
            'scope' => '',
        ]);

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Token refreshed.',
            'data' => $response->json(),
        ], 200);
    }

    /**
     * Logout
     */
    public function logout (Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        //$response = ['message' => 'You have been successfully logged out!'];
        //return response($response, 200);
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'You have been successfully logged out!',
            'data' => $token,
        ], 200);
    }
}
