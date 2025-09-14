<?php

namespace App\Traits;

trait AuthErrorResponses
{
    // Email not found
    protected function emailError()
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors'  => [
                'email' => ['This email is not found in our records.']
            ]
        ], 422);
    }

    // Password incorrect
    protected function passwordError()
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors'  => [
                'password' => ['The password you entered is incorrect.']
            ]
        ], 422);
    }

    // Generic login failure
    protected function genericLoginFailure()
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors'  => [
                'login' => ['Email or password is incorrect.']
            ]
        ], 401);
    }
}
