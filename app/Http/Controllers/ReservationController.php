<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    // Получить все бронирования
    public function index()
    {
        return response()->json(Reservation::all());
    }

    // Создать новое бронирование
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'table_number' => 'required|integer',
            'date' => 'required|date',
            'time' => 'required|string'
        ]);

        $reservation = Reservation::create($validated);
        return response()->json($reservation, 201);
    }

    // Удалить бронирование
    public function destroy($id)
    {
        Reservation::destroy($id);
        return response()->json(['message' => 'Бронирование удалено']);
    }
}
