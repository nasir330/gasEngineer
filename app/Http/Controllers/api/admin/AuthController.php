<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
      use HttpResponses;
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
            $user = User::firstWhere('email', $validation['email']);

            if (!$user) {
                  return $this->error(['email' => 'No user registered with this email'], 'Invalid credentials', 401);
            }

            if (!Hash::check($validation['password'], $user->password)) {
                  return $this->error(['password' => 'Incorrect password'], 'Invalid credentials', 401);
            }
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
