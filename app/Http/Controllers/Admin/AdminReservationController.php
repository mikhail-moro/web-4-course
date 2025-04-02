<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminReservationController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'table_id' => 'required|integer|exists:tables,id',
            'guests' => 'required|integer|min:1',
            'confirmation_code' => 'string',
            'confirmed' => 'boolean',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $reservation = Reservation::create($validated);

        return response()->json($reservation, 201);
    }

    public function read(Request $request)
    {
        $page = $request->input('page', 1);
        $reservations = Reservation::with(['user:id,email,name', 'table:id,number'])
            ->paginate(20, ['*'], 'page', $page);

        $reservations->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'user_email' => $item->user->email,
                'user_name' => $item->user->name,
                'table_id' => $item->table_id,
                'table_number' => $item->table->number,
                'guests' => $item->guests,
                'confirmation_code' => $item->confirmation_code,
                'confirmed' => $item->confirmed,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'start' => $item->start,
                'end' => $item->end,
            ];
        });

        return response()->json($reservations);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'integer|exists:users,id',
            'table_id' => 'integer|exists:tables,id',
            'guests' => 'integer|min:1',
            'confirmation_code' => 'string',
            'confirmed' => 'boolean',
            'start' => 'date',
            'end' => 'date|after:start',
        ]);

        $reservation->update($validated);

        return response()->json($reservation);
    }

    public function delete($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return response()->json(null, 204);
    }
}
