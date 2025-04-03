<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use ValueError;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|integer|exists:tables,id',
            'guests' => 'required|integer|min:1',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);
        $validated["confirmation_code"] = Reservation::generateConfirmationCode();
        $validated["confirmed"] = false;
        $validated["user_id"] = $request["auth_user_id"];

        if (Table::hasNotEnoughSeats($validated["table_id"], $validated["guests"])) {
            throw ValidationException::withMessages(["guests" => "Недостаточно мест."]);
        }
        if (Reservation::hasOverlappingReservations($validated["table_id"], $validated["start"], $validated["end"])) {
            throw ValidationException::withMessages([
                "start" => "Столик на это время занят.", "end" => "Столик на это время занят."
            ]);
        }

        $reservation = Reservation::create($validated);

        Mail::to(User::findOrFail($validated["user_id"])["email"])
            ->send("mail", ["confirmation_code" => $validated["confirmation_code"]]);

        return response()->json($reservation, 201);
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $reservations = Reservation::where("user_id", $request["auth_user_id"])->paginate(20, ['*'], 'page', $page);
        return response()->json($reservations);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'guests' => 'integer|min:1',
            'start' => 'date',
            'end' => 'date|after:start',
        ]);

        if (!Table::hasEnoughSeats($validated["table_id"], $validated["guests"])) {
            throw ValidationException::withMessages(["guests" => "Недостаточно мест."]);
        }
        if (Reservation::hasOverlappingReservations($validated["table_id"], $validated["start"], $validated["end"])) {
            throw ValidationException::withMessages([
                "start" => "Столик на это время занят.", "end" => "Столик на это время занят."
            ]);
        }

        $reservation->update($validated);

        return response()->json($reservation);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return response()->json(null, 204);
    }

    public function confirm(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation["confirmation_code"] != $request->input("code")) {
            throw new ValueError("Ошибка подтверждения.");
        }

        $reservation->update(["confirmed" => true]);

        return response()->json(null, 204);
    }
}
