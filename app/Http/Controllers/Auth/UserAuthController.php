<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use Illuminate\Support\Facades\Auth;
// use Laravel\Passport\TokenRepository;
// use Laravel\Passport\RefreshTokenRepository;
// use Laravel\Passport\PersonalAccessTokenResult;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;
        $refreshToken = $this->generateRefreshToken($user);

        return response([ 'user' => $user, 'token' => $token, 'resreshToken' => $refreshToken]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required|min:8'
        ]);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'Incorrect Details. Please try again']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['user' => auth()->user(), 'token' => $token]);

    }

    //     /**
    //  * Login user
    //  */
    // public function login(Request $request)
    // {
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $user = Auth::user();

    //         // Create a personal access token
    //         $tokenResult = $user->createToken('Personal Access Token');
    //         $accessToken = $tokenResult->accessToken;
    //         //$refreshToken = $tokenResult->token;
    //         $refreshToken = RefreshTokenRepository::class;
    //         $result = $refreshToken->create();


    //         // $response = Http::post(env('APP_URL') . '/oauth/token', [
    //         //     'grant_type' => 'password',
    //         //     'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
    //         //     'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
    //         //     'username' => $request->email,
    //         //     'password' => $request->password,
    //         //     'scope' => '',
    //         // ]);

    //         return response()->json([
    //             'access_token' => $accessToken,
    //             'refresh_token' => $refreshToken->refresh_token,
    //             'token_type' => 'Bearer',
    //             'expires_at' => $tokenResult->token->expires_at,
    //             'data' => $user,
    //             'tokenResult' => $tokenResult
    //         ]);
    //         // $user['token'] = $response->json();

    //         // return response()->json([
    //         //     'success' => true,
    //         //     'statusCode' => 200,
    //         //     'message' => 'User has been logged successfully.',
    //         //     'data' => $user,
    //         // ], 200);
    //     } else {
    //         return response()->json([
    //             'success' => true,
    //             'statusCode' => 401,
    //             'message' => 'Unauthorized.',
    //             'errors' => 'Unauthorized',
    //         ], 401);
    //     }

    // }

    /**
     * Login user
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

    public function logout (Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    protected function generateRefreshToken($user)
    {
        return base64_encode(json_encode([
            'user_id' => $user->id,
            'expires_at' => now()->addDays(30)->timestamp,
        ]));
    }
}


// use App\Http\Controllers\Controller;
// use App\Http\Requests\LoginRequest;
// use App\Http\Requests\RefreshTokenRequest;
// use App\Http\Requests\RegisterRequest;
// use App\Models\User;
// use Illuminate\Http\JsonResponse;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Http;

// class UserAuthController extends Controller
// {
//     /**
//      * User registration
//      */
//     public function register(RegisterRequest $request): JsonResponse
//     {
//         $userData = $request->validated();

//         $userData['email_verified_at'] = now();
//         $user = User::create($userData);

//         $response = Http::post('http://localhost' . '/oauth/token', [
//             'grant_type' => 'password',
//             'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
//             'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
//             'username' => $userData['email'],
//             'password' => $userData['password'],
//             'scope' => '',
//         ]);

//         $user['token'] = $response->json();

//         return response()->json([
//             'success' => true,
//             'statusCode' => 201,
//             'message' => 'User has been registered successfully.',
//             'data' => $user,
//         ], 201);
//     }

//     /**
//      * Login user
//      */
//     public function login(LoginRequest $request): JsonResponse
//     {
//         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
//             $user = Auth::user();

//             $response = Http::post(env('APP_URL') . '/oauth/token', [
//                 'grant_type' => 'password',
//                 'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
//                 'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
//                 'username' => $request->email,
//                 'password' => $request->password,
//                 'scope' => '',
//             ]);

//             $user['token'] = $response->json();

//             return response()->json([
//                 'success' => true,
//                 'statusCode' => 200,
//                 'message' => 'User has been logged successfully.',
//                 'data' => $user,
//             ], 200);
//         } else {
//             return response()->json([
//                 'success' => true,
//                 'statusCode' => 401,
//                 'message' => 'Unauthorized.',
//                 'errors' => 'Unauthorized',
//             ], 401);
//         }

//     }

//     /**
//      * Login user
//      *
//      * @param  LoginRequest  $request
//      */
//     public function me(): JsonResponse
//     {

//         $user = auth()->user();

//         return response()->json([
//             'success' => true,
//             'statusCode' => 200,
//             'message' => 'Authenticated use info.',
//             'data' => $user,
//         ], 200);
//     }

//     /**
//      * refresh token
//      *
//      * @return void
//      */
//     public function refreshToken(RefreshTokenRequest $request): JsonResponse
//     {
//         $response = Http::asForm()->post(env('APP_URL') . '/oauth/token', [
//             'grant_type' => 'refresh_token',
//             'refresh_token' => $request->refresh_token,
//             'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
//             'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
//             'scope' => '',
//         ]);

//         return response()->json([
//             'success' => true,
//             'statusCode' => 200,
//             'message' => 'Refreshed token.',
//             'data' => $response->json(),
//         ], 200);
//     }

//     /**
//      * Logout
//      */
//     public function logout(): JsonResponse
//     {
//         Auth::user()->tokens()->delete();

//         return response()->json([
//             'success' => true,
//             'statusCode' => 204,
//             'message' => 'Logged out successfully.',
//         ], 204);
//     }
// }*/
