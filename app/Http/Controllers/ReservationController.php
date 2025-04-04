<?php

namespace App\Http\Controllers;

use App\Mail\Confirmation;
use App\Models\User;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

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
        $validated["user_id"] = Auth::id();

        if (Table::hasNotEnoughSeats($validated["table_id"], $validated["guests"])) {
            throw ValidationException::withMessages(["guests" => "Недостаточно мест."]);
        }
        if (Reservation::hasOverlappingReservations($validated["table_id"], $validated["start"], $validated["end"])) {
            throw ValidationException::withMessages([
                "start" => "Столик на это время занят.", "end" => "Столик на это время занят."
            ]);
        }

        $reservation = Reservation::create($validated);
        $email = User::findOrFail($validated["user_id"])["email"];

        Mail::to($email)->send(new Confirmation($validated["confirmation_code"]));

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
        $reservation = Reservation::query()->findOrFail($id);
        $request->mergeIfMissing($reservation->only(["guests", "start", "end"]));

        $validated = $request->validate([
            'guests' => 'integer|min:1',
            'start' => 'date',
            'end' => 'date|after:start',
        ]);

        if (Table::hasNotEnoughSeats($reservation["table_id"], $validated["guests"])) {
            throw ValidationException::withMessages(["guests" => "Недостаточно мест."]);
        }

        if (Reservation::hasOverlappingReservations(
            $reservation["table_id"], $validated["start"],  $validated["end"], $reservation["id"]
        )) {
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
        $validated = $request->validate([
            'confirmation_code' => 'integer|min:1000|max:9999',
        ]);
        $reservation = Reservation::findOrFail($id);

        if ($reservation["confirmation_code"] != $validated["confirmation_code"]) {
            return response()->json(["message" => "Ошибка подтверждения."], 422);
        }

        $reservation->update(["confirmed" => true]);

        return response()->json(null, 204);
    }
}
