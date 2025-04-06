<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Notifications\ResetPasswordNotification;
use App\Models\User;



class PasswordResetController extends Controller
{
public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email']);

    // Отправка ссылки для сброса пароля с кастомным уведомлением
    $status = Password::sendResetLink(
        $request->only('email'),
        function ($user, $token) {
            $user->notify(new ResetPasswordNotification($token)); // Передаем токен в уведомление
        }
    );

    return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'Ссылка для сброса пароля отправлена на вашу почту.'])
                : response()->json(['message' => 'Не удалось отправить ссылку для сброса пароля.'], 500);
}

    public function resetPassword(Request $request)
    {
        // Валидация данных
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Попытка сбросить пароль
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response()->json(['message' => 'Пароль успешно сброшен.'])
                    : response()->json(['message' => 'Не удалось сбросить пароль.'], 500);
    }
}