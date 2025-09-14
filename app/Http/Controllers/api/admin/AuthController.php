<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Traits\AuthErrorResponses;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
      use HttpResponses, AuthErrorResponses;
      // Register method
      public function register(UserStoreRequest $request)
      {
            $validation = $request->Validated();
            $user = User::create([
                  'name' => $validation['name'],
                  'email' => $validation['email'],
                  'password' => Hash::make($validation['password']),
                  'avatar' => $validation['avatar'] ?? null,
            ]);
            return $this->success([
                  'user' => $user,
                  'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
            ]);
      }
      // Login method
      public function login(UserLoginRequest $request)
      {
            $validation = $request->validated();
            $email = $validation['email'];
            $password = $validation['password'];

            //rate limiter for too many login attempts
            $throttleKey = Str::lower($email) . '|' . $request->ip();
            $maxAttempts = 5;
            $delaySeconds = 60;
            if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
                  $seconds = RateLimiter::availableIn($throttleKey);
                  return response()->json([
                        'message' => "Too many login attempts. Try again in $seconds seconds.",
                  ], 429);
            }
            //check user credentials
            $user = User::firstWhere('email', $validation['email']);

            if (!$user) {
                  RateLimiter::hit($throttleKey, $delaySeconds);
                  return $this->emailError();
            }
            if (!Hash::check($validation['password'], $user->password)) {
                  RateLimiter::hit($throttleKey, $delaySeconds);
                  return $this->passwordError();
            }
            // clear attempts on successful login
            RateLimiter::clear($throttleKey);

            return $this->success([
                  'user' => $user,
                  'token' => $user->createToken('API token of ' . $user->name)->plainTextToken
            ]);
      }

      // Logout method
      public function logout(Request $request)
      {
            $user = Auth::user();

            if ($user && $user->currentAccessToken()) {
                  $user->currentAccessToken()->delete();
                  return $this->success([], 'You have been logged out successfully');
            }

            return $this->error([], 'No active token found', 401);
      }
}
