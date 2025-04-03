<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  // Вход пользователя
  public function login(Request $request)
  {
      $request->validate([
          'email' => 'required|string|email',
          'password' => 'required|string',
      ]);

      if (!Auth::attempt($request->only('email', 'password'))) {
          throw ValidationException::withMessages([
              'email' => ['Invalid credentials'],
          ]);
      }

      $user = Auth::user();
      #$token = base64_encode($user->id . '|' . now()); Безвременный токен

        $expiry = now()->addHour()->timestamp; // Время истечения 1 час
        $token = base64_encode($user->id . '|' . $expiry);


      return response()->json([
          'message' => 'Login successful',
          'user' => $user,
          'token' => $token
      ]);
  }
}
